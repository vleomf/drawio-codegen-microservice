<?php
namespace App\Factories;

class MXCellFactory implements IMXCellFactory
{
    public function getMXAttribute( $simpleXmlElement ) : MXAttribute {
        //  Obtener el valor de SimpleXmlElement
        //  En este indice se encuentran los datos que necesitamos
        //  parsear.

        //  Parseamos nivel de encapsulamiento
        $value = trim( $simpleXmlElement['value'] );
        $encapsulationLevel = $value[0];

        //  Parseamos nombre y tipo de retorno
        $value = trim( substr( $simpleXmlElement['value'], 1 ) );
        $value = explode( ':', $value );
        $name  = trim( $value[0] );
        $returnType = trim( $value[1] );

        return new MXAttribute(
            $simpleXmlElement['id'],
            $name, $encapsulationLevel, $returnType
        );
    }

    public function getMXClass( $simpleXmlElement) : MXClass {
        //  Primero obtenemos parametros 
        return new MXClass(
            $simpleXmlElement['id'],
            $simpleXmlElement['value']
        );
    }

    public function getMXMethod($simpleXmlElement) : MXMethod {

        $value = trim( $simpleXmlElement['value'] );
        $encapsulationLevel = $value[0];
        
        $value = trim ( $simpleXmlElement ['value'] );
        $parameters = $value[0];


        $value = trim ( substr ( $simpleXmlElement ['value'], 1) );
        $value = explode (':', $value );
        $name  = trim($value[0] );
        $returnType = trim( $value [1] );

        return new MxMethod (
            $simpleXmlElement['id'],
            $name, $encapsulationLevel, $parameters, $returnType
        );
    }

    public function getMXRelationship($simpleXmlElement, $type) : MXRelationship {
        $id = $simpleXmlElement ['id'];
        $relationshipType = $type;
        $target = $simpleXmlElement ['source'];

        return new MXRelationship(
             $simpleXmlElement['id'],
            $relationshipType, $target
        );
    }
}