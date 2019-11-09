<?php
namespace App\Factories;

class MXMethod implements IMXCell 
{
    public $id;
    public $name;
    public $encapsulationlevel;
    public $parameters;
    public $returnType;

    public function __construct($id, $name, $encapsulationlevel, $parameters, $returnType) {
        $this->id = $id;
        $this->name = $name;
        $this->encapsulationLevel = $encapsulationlevel;
        $this->parameters = $parameters;
        $this->returnType = $returnType;
    }

    public function toString($language) : string {
        if( strtolower($language) == 'php' ){
            $this->phpString();
        }
    }

    private function phpString(){
        //  Crear el equivalente de esta clase en PHP
    }
}