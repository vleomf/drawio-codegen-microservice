<?php
namespace App\Factories;
use App\Factories\IMXCell;

class MXClass implements IMXCell
{
    public $id;
    public $name;
    public $attributes;
    public $methods;
    public $relationships;

    public function __construct($id, $name, $attributes, $methods, $relationships) {
        $this->id = $id;
        $this->name = $name;
        $this->attributes = $attributes;
        $this->methods = $methods;
        $this->relationships = $relationships;
    }

    public function toString($language) {
        if( strtolower( $language ) == 'php')
        {
            $this->phpString();
        }
    }

    private function phpString() {
        //  Aqui creamos el equivalente en PHP de esta clase
    }
}