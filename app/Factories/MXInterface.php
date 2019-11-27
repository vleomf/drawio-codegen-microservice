<?php
namespace App\Factories;

class MXInterface implements IMXCell
{
    public $id;
    public $name;
    public $methods;
    

    //  NOTA: Consturctor debe recibir todos los parametros o solo un SimpleXMLElement
    public function __construct($id, $name, $methods) {
        $this->id = $id;
        $this->name = $name;
        $this->methods = $methods;
    }

    public function toString($language) : string {
        if( strtolower( $language ) == "php" ) {
            return $this->phpString();
        }
    }

    public function insertRelationshipType ( $relationship ) :void {
        $this->relationships[] = $relationship;

    }

    private function phpString() {
        $codeString = "\n";

        //  Iniciamos interface
        $interfaceName = $this->name;
        $codeString .= "interface $interfaceName {\n";        
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