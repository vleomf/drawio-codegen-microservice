<?php
namespace App\Factories;

class MXInterface implements IMXCell
{
    public $id;
    public $name;
    public $methods;
    

    //  NOTA: Consturctor debe recibir todos los parametros o solo un SimpleXMLElement
    public function __construct($id, $name, $methods) {
        $this->id = $id;
        $this->name = $name;
        $this->methods = $methods;
    }

    public function toString($language) : string {
        if( strtolower( $language ) == "php" ) {

        }
    }

    private function phpString() {
        //  ERste metodo convierte esta clase en su equivalente en PHP (texto)
    }
}