<?php
namespace App\Factories;
use App\Factories\IMXCell;

class MXAttribute implements IMXCell
{
    public $id;
    public $name;
    public $encapsulationLevel;
    public $dataType;

    //  NOTA: Consturctor debe recibir todos los parametros o solo un SimpleXMLElement
    public function __construct($id, $name, $encapsulationLevel, $dataType) {
        $this->$id = $id;
        $this->name = $name;
        $this->encapsulationLevel = $encapsulationLevel;
        $this->dataType = $dataType;
    }

    public function toString($language) {
        if( strtolower( $language ) == "php" ) {

        }
    }

    private function phpString() {
        //  ERste metodo convierte esta clase en su equivalente en PHP (texto)
    }
}