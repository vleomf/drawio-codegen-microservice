<?php
namespace App\Factories;

class MXClass implements IMXCell
{
    public $id;
    public $name;
    public $attributes;
    public $methods;
    public $relationships;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function insertAttribute( $attribute ) : void {
        $this->attributes[] = $attribute;
    }

    public function toString($language) : string {
        if( strtolower( $language ) == 'php')
        {
            $this->phpString();
        }
    }

    private function phpString() {
        //  Aqui creamos el equivalente en PHP de esta clase
    }
}