<?php
namespace App\Factories;
use App\Factories\IMXCell;

class MXRelationships implements IMXCell
{
    public $id;
    public $relationshipType;
    public $target;

    public function __construct($id, $relationshipType, $target){
        $this->id = $id;
        $this->relationshipType = $relationshipType;
        $this->target = $target;
    }

    public function toString( $language ) {
        if( strtolower($language) == 'php' ) {
            $this->phpString();
        }
    }

    private function phpString() {
        //  esta clase retorna el equivalente en PHP
    }
}