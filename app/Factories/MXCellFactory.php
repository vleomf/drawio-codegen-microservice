<?php
namespace App\Factories;

use App\Factories\MXAttribute;
use App\Factories\MXClass;
use App\Factories\MXMethod;
use App\Factories\MXRelationship;

class MXCellFactory implements  App\Factories\IMXCellFactory
{
    public function getMXAttribute( $simpleXmlElement ){
        //  Primero obtenemos parametros 
        return new MXAttribute(/** AQUI PASAMOS PARAMETROS */);
    }

    public function getMXClass( $simpleXmlElement) {
        return new MXClass(/**AQUI PASAMOS LOS PARAMS */);
    }

    public function getMXMethod($simpleXmlElement) {
        return new MXMethod(/**AQUI PASAMOS LOS PARAMS */);
    }

    public function getMXRelationship($simpleXmlElement) {
        return new MXRelationship(/**AQUI PASAMOS LOS PARAMS */);
    }
}