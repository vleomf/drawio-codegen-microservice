<?php 
namespace App\Strategies;
use App\Factories\IMXCell;

class MXHardClassifier implements IMXClassifier 
{
    /**
     *  @param  \SimpleXmlElement Elemento de XML que genera nuestro MXFile
     *  @return string Regresa el tipo de elemento que corresponde, este puede ser...
     *                 - class
     *                 - attribute
     *                 - method
     *                 - relationship
     */
    public function classify($SimpleXmlNode) : string 
    {
        //  Variable de control
        $clasificacion = "";

        //  Aplicamos primer filtro, verificamos que es elemento UML
        if( !$this->isUMLElement($SimpleXmlNode) ) 
        {
            // Si no es un elemento UML, se regresa por defualt
            // cadena vacia, para terminar ejecuion.
            return "";
        }

        //  Aplicamos segundo filtro, verificamos si es un elemento global
        //  Nota: Los elementos globales son Class, Relationship
        if( $this->isGlobalElement($SimpleXmlNode) ) 
        {
            $clasificacion = $this->parseStyleAttribute($SimpleXmlNode);
        }
        else
        {
            //  Atributo, Metodo
            $clasificacion = "anidado";
        }

        return $clasificacion;
    }

    /**
     *  Este metodo es el primer filtro de clasificacion.
     *  Define si el elemento a clasificar es un elemento UML o no.
     * 
     *  @param \SimpleXmlElement Elemento de XML que genera nuestro MXFile
     *  @return bool Define si el nodo es un elemento UML
     */
    private function isUMLElement($SimpleXmlNode) : bool
    {
        if($SimpleXmlNode["id"] == "0" || $SimpleXmlNode["id"] == "1")
        {
            return false;
        }
        return true;
    }

     /**
     *  Este metodo es el segundo filtro de clasificacion.
     *  Define si el elemento a clasificar es un elemento global en el archivo.
     * 
     *  @param \SimpleXmlElement Elemento de XML que genera nuestro MXFile
     *  @return bool Define si el nodo es un elemento UML global
     */
    private function isGlobalElement($SimpleXmlNode) : bool
    {
        if($SimpleXmlNode["parent"] == "1")
        {
            return true;
        }
        return false;
    }

    /**
     *  Este metodo parsea el atributo de estilo, nos permite ver si es 
     *  un elemento class o relationship
     * 
     *  @param \SimpleXmlElement Elemento de XML que genera nuestro MXFile
     *  @return bool Define si el nodo es un elemento UML global
     */
    private function parseStyleAttribute($SimpleXmlNode) : string
    {
        $styleDict = []; // Diccionario de estilos
        $styles = explode( ';', $SimpleXmlNode['style'] );
        
        //  Si el parametro en la posicion 0 es endArrow, entonces 
        //  este elemento es una flecha de relacion
        //  NOTA: Queda por verificar los valores de endArrow para
        //  todas las demas flechas de relacion

        //  Ahorita sabemos que la flecha de Especializacion (herencia)
        //  tiene el valor endArrow = block

        foreach( $styles as $style )
        {
            $keyval = explode( "=" , $style );
            if(isset($keyval[0]) && isset($keyval[1]))
            {
                $styleDict[$keyval[0]] = $keyval[1];
            }
        }

        if( isset( $styleDict['endArrow'] ) && isset( $styleDict['endFill'] ) )
        {
            if( $styleDict['endArrow'] == 'block' && $styleDict['endFill'] == 0) {
                return "inheritance";
            }
        }

        return "class";
    }

    private function parseValueAttribute($SimpleXmlNode) : string
    {

    }
}