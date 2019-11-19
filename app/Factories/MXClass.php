<?php
namespace App\Factories;

class MXClass implements IMXCell
{
    public $id;
    public $name;
    public $attributes;
    public $methods;
    public $relationships;
    private $mxNodes;       //  Referencia de los otros nodos

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function insertAttribute( $attribute ) : void {
        $this->attributes[] = $attribute;
    }

    public function insertMethod( $method ) : void {
        $this->methods[] = $method;
    }
    
    public function insertRelationshipType ( $relationship ) :void {
        $this->relationships[] = $relationship;

    }

    public function toString($language) : string {
        if( strtolower( $language ) == 'php')
        {
            return $this->phpString();
        }
    }

    private function phpString() {
        //  Aqui creamos el equivalente en PHP de esta clase
        //var_dump(); die;
        //$outputString  = "<?php\n";    
        $outputString = "class $this->name "; 

        //  Organizamos las relaciones
        $totalImplementations = [];
        $totalExtensions = [];
        //var_dump(is_null($this->relationships)); die;
        //Validamos que relationships no sea nulo
        if ($this->relationships != null){
                foreach($this->relationships as $relationship)
            {
                if($relationship->relationshipType == 'implementation')
                {
                    $totalImplementations[] = $this->mxNodes[$relationship->target];
                }
                if($relationship->relationshipType == 'inheritance')
                {
                    $totalExtensions[] = $this->mxNodes[$relationship->target];
                }
            }

        }
        
        //  Interpolamos relaciones
        //  1) Si no hay tipos de relaciones entonces cerramos 
        //  llaves de clase
        $outputString .= ( count($totalImplementations) + count($totalExtensions) == 0) ? "{\n" : "";
        //  2) Interpolamos extensiones
        if(count($totalExtensions) > 0)
        {
            for($i = 0; $i < count($totalExtensions); $i++)
            {
                $extension = $totalExtensions[$i];
                if($i == 0)
                {
                    $outputString .= "extends ";
                }
                $outputString .= "$extension->name, ";
            }
            $outputString = substr($outputString, 0, -2);
        }
        // 3) Interpolamos implementaciones
        if(count($totalImplementations) > 0)
        {
            for($i = 0; $i < count($totalImplementations); $i++)
            {
                $implementation = $totalImplementations[$i];
                if($i == 0)
                {
                    $outputString .= "implements ";
                }
                $outputString .= "$implementation->name, ";
            }
            $outputString = substr($outputString, 0, -2);
        }

        $outputString.= "{\n";
        
        //  Interpolamos variables
        if(isset($this->attributes))
        {
            foreach($this->attributes as $attribute)
            {
                $encapsulationLevel = $this->encapsulationLevelToString($attribute->encapsulationLevel);
                $outputString .= "\t$encapsulationLevel $$attribute->name;\n";
            }
        }

        //  Interpolamos metodos
        if(isset($this->methods))
        {
            foreach($this->methods as $method)
            {
                $encapsulationLevel = $this->encapsulationLevelToString($method->encapsulationLevel);
                $outputString .= "\t$encapsulationLevel function $method->name(){}\n";
            }
        }

        //  Finalizamos bloque de clase
        $outputString .= "}";
        
        return $outputString;
    }

     /**
     *  NOTA. Los metodos descritos a partir de aqui en adelante
     *        deben ser abstraidos en otra clase, para evitar duplicidad 
     *        de código
     */

    public function setRelationshipNodesReferences($mxNodes) : void
    {
        $this->mxNodes = $mxNodes;
    }

    private function encapsulationLevelToString($encapsulationLevel) {
        switch($encapsulationLevel)
        {
            case '+':
                return 'public';
                break;
            case '-':
                return 'private';
                break;
            case '#';
                return 'protected';
                break;
        }
    }
}