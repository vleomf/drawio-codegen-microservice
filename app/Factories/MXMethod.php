<?php
namespace App\Factories;

class MXMethod implements IMXCell 
{
    public $id;
    public $name;
    public $encapsulationLevel;
    public $parameters;
    public $returnType;

    public function __construct($id, $name, $encapsulationLevel, $parameters, $returnType) {
        $this->id = $id;
        $this->name = $name;
        $this->encapsulationLevel = $encapsulationLevel;
        $this->parameters = $parameters;
        $this->returnType = $returnType;
    }

    public function toString($language) : string {
        if( strtolower($language) == 'php' ){
            return $this->phpString();
        }
    }

    private function phpString(){
        $encapsulationLevel = $this->encapsulationLevelToString($this->encapsulationLevel);
        $methodName = $this->name;
        $outputString = "\t$encapsulationLevel function $methodName( ";

        //  Parseamos los parámetros
        foreach($this->parameters as $parameter)
        {
            //var_dump($parameter);
            $name     = $parameter->name;
            $dataType = $parameter->dataType;
            $outputString .= "$name: $dataType, ";
        }
        if(count($this->parameters)) $outputString = substr($outputString, 0, -2);
        $outputString .= " ){}\n";
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