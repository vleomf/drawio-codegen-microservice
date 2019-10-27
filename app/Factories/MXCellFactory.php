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
        return new MXMethod(/**AQUI PASAMOS LOS PARAMS */);
    }

    public function getMXRelationship($simpleXmlElement) : MXRelationship{
        return new MXRelationship(/**AQUI PASAMOS LOS PARAMS */);
    }
}