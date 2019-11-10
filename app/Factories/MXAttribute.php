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
        //  ERste metodo convierte esta clase en su equivalente en PHP (texto)
    }
}