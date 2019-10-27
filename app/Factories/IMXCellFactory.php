<?php
namespace App\Factories;

interface IMXCellFactory 
{
    //  Debe retornar MXAttribute
    public function getMXAttribute( $simpleXmlElement );

    //  Debe retornar MXClass
    public function getMXClass( $simpleXmlElement );

    //  Deber retornar MXMethod
    public function getMXMethod( $simpleXmlElement );

    //  Deber retornar MXRelationship
    public function getMXRelationship( $simpleXmlElement );

}