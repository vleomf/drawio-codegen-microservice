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
    public function classify(& $SimpleXmlNode) : string 
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
            //  Verificamos si es una INTERFAZ y evitamos que
            //  se haga el parseo por estilo y se obtienen datos de 
            //  valor, se ejecuta en método dedicado debido
            //  a la naturaleza del elemento UML en Drawio
            if($this->isInterface($SimpleXmlNode))
            {
                //  Terminamos de validar el elemento y 
                //  regresamos el valor directamente
                //  para terminar la ejecución del resto 
                //  de filtos;
                //  var_dump($SimpleXmlNode); 
                return 'interface';
            }

            $clasificacion = $this->parseStyleAttribute($SimpleXmlNode);
            $this->trimValueAttribute($SimpleXmlNode); // Algunas clases insertan HTML
        }
        else
        {
            //  Si no es elemento global, verificamos si es atributo o metodo
            $clasificacion = $this->parseValueAttribute($SimpleXmlNode['value']);
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
     *  @return string 
     */
    private function parseStyleAttribute($SimpleXmlNode) : string
    {
        $styleDict = []; // Diccionario de estilos
        $styles = explode( ';', $SimpleXmlNode['style'] );

        //  Convertimos la lista de estilos a un diccionario de estilos
        //  para facilitar la busqueda
        foreach( $styles as $style )
        {
            $keyval = explode( "=" , $style );
            if(isset($keyval[0]) && isset($keyval[1]))
            {
                $styleDict[$keyval[0]] = $keyval[1];
            }
        }

        //  Buscamos en el diccionario de estilos si las llaves 'endArrow' 
        //  y 'endFill' existen...
        if( isset( $styleDict['endArrow'] ) && isset( $styleDict['endFill'] ) )
        {
            //  Si los valores son 'block' y '0', se supone que es la flecha de "generalization"
            if( $styleDict['endArrow'] == 'block' && $styleDict['endFill'] == '0') {
                //  Si los valores son los mismos de "generalizacion", pero las lineas son punteadas
                if( isset( $styleDict['dashed'] ) && $styleDict['dashed'] == '1' )
                {
                    return "implementation";
                }
                return "inheritance";
            }

            

            //  Si los valores son 'diamondThin' y '1', se supone que es la flecha de "composicion 2"
            if( strtolower( $styleDict['endArrow'] ) == 'diamondthin' && $styleDict['endFill'] == '1') {
                return "composition";
            }

            //  Si los valores son 'diamondThin' y '1', se supone que es la flecha de "aggregation 2"
            if( strtolower( $styleDict['endArrow'] ) == 'diamondthin' && $styleDict['endFill'] == '0') {
                return "aggregation";
            }
        }

        //  Buscamos en el diccionario de estilos si las llaves 'startArrow'
        //  y 'startFill' existen...
        if( isset( $styleDict['startArrow'] ) && isset( $styleDict['startFill'] ) )
        {
            //  Si los valores son 'diamondThin' y '1', se supone que es la flecha de "composicion 1"
            if( strtolower( $styleDict['startArrow'] ) == 'diamondthin' && $styleDict['startFill'] == '1') {
               return "composition";
            } 

            //  Si los valores son 'diamondThin' y '0', se supone que es la flecha de "agregacion 1"
            if( strtolower( $styleDict['startArrow'] ) == 'diamondthin' && $styleDict['startFill'] == '0') {
                return "aggregation";
            } 
        }

        //var_dump($styleDict); 
        
        //  Se asume que por default todo elemento global es una clase 
        //  (por el momento no se toma en cuenta las interfaces)
        return "class";
    }

    /**
     *  Este metodo parsea el atributo de valor, determina si es 
     *  attribute o method
     * 
     *  @param \SimpleXmlElement Elemento de XML que genera nuestro MXFile
     *  @return string 
     */
    private function parseValueAttribute($value) : string
    {
        $attributeRegex = "/(\+|\-|\#)\s?\w+\s?:\s?.*/";
        $functionRegex  = "/(\+|\-|\#)\s?\w+\s?\(\s?(.*?)\s?\).*/";
    
        if(preg_match_all($attributeRegex, $value)) return "attribute";
        if(preg_match_all($functionRegex,  $value)) return "method";

        return "";
    }

    public static function ClassifyAtributeOrMethod($value) : string
    {
        $attributeRegex = "/(\+|\-|\#)\s?\w+\s?:\s?.*/";
        $functionRegex  = "/(\+|\-|\#)\s?\w+\s?\(\s?(.*?)\s?\).*/";
    
        if(preg_match_all($attributeRegex, $value)) return "attribute";
        if(preg_match_all($functionRegex,  $value)) return "method";

        return "";
    }


    /**
     *  Este metodo elimina cualquier entidad HTML en el valor del nodo,
     *  dejando solo el texto que nos interesa.
     */
    private function trimValueAttribute(& $SimpleXmlNode) : void
    {
        $trimRegex = "/\<.*?\s?\>/";
        $trimmedValue = preg_replace($trimRegex, '', $SimpleXmlNode['value']);
        $SimpleXmlNode['value'] = $trimmedValue;
    }

    /**
     * Este metodo determina si el elemento a evaluar es una INTERFAZ
     */
    private function isInterface(& $SimpleXmlNode) : bool
    {
        $isInterface = false;
        if(preg_match('/&lt;&lt;Interface&gt;&gt;/', $SimpleXmlNode['value'])) $isInterface = true;
        return $isInterface;
    }
}