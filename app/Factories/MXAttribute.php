<?php
namespace App\Factories;

class MXAttribute implements IMXCell
{
    public $id;
    public $name;
    public $encapsulationLevel;
    public $dataType;

    //  NOTA: Consturctor debe recibir todos los parametros o solo un SimpleXMLElement
    public function __construct($id, $name, $encapsulationLevel, $dataType) {
        $this->id = $id;
        $this->name = $name;
        $this->encapsulationLevel = $encapsulationLevel;
        $this->dataType = $dataType;
    }

    public function toString($language) : string {
        if( strtolower( $language ) == "php" ) {

        }
    }

    private function phpString() {
        $encapsulationLevel = $this->encapsulationLevelToString($this->encapsulationLevel);
        $attributeName = $this->name;
        $outputString = "\t$encapsulationLevel $attributeName;\n";
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