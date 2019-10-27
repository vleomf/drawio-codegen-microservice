<?php
namespace App\Factories;

class MXRelationships implements IMXCell
{
    public $id;
    public $relationshipType;
    public $target;

    /*public function __construct($id, $relationshipType, $target){
        $this->id = $id;
        $this->relationshipType = $relationshipType;
        $this->target = $target;
    }*/

    public function __construct() {}

    public function toString( $language ) : string {
        if( strtolower($language) == 'php' ) {
            $this->phpString();
        }
    }

    private function phpString() {
        //  esta clase retorna el equivalente en PHP
    }
}