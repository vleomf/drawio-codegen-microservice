<?php
namespace App\Factories;

class MXRelationship implements IMXCell
{
    public $id;
    public $relationshipType;
    public $target;

    public function __construct($id, $relationshipType, $target){
        $this->id = $id;
        $this->relationshipType = $relationshipType;
        $this->target = $target;
    }

    //public function __construct() {}

    public function toString( $language ) : string {
        if( strtolower($language) == 'php' ) {
            $this->phpString();
        }
    }

    private function phpString() {
        //  esta clase retorna el equivalente en PHP
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