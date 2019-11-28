<?php
namespace App\Factories;
use App\Strategies\MXHardClassifier;

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
            $simpleXmlElement['id']->__toString(),
            $name, $encapsulationLevel, $returnType
        );
    }

    public function getMXClass( $simpleXmlElement) : MXClass {
        //  Primero obtenemos parametros 
        return new MXClass(
            $simpleXmlElement['id']->__toString(),
            $simpleXmlElement['value']->__toString()
        );
    }

    public  function getMXMethod( $simpleXmlElement ) : MXMethod
    {
        $result = $this->parseMXMethod($simpleXmlElement['value']);
        return new MXMethod (
            $simpleXmlElement['id']->__toString(),
            $result['methodNameSubtring'], 
            $result['encapsulationLevel'], 
            $result['mxParameters'],
            $result['returnTypeSubstring']
        );
    }

    private function parseMXMethod($cadenaMetodo) {

        //  Obtenemos el primer elemento de la cadena
        //  Por convención de UML, el primer caracter debe ser 
        //  El nivel de encapsulamiento
        $value = trim( $cadenaMetodo );
        $encapsulationLevel = $value[0];    

        //  Removemos el primer caracter y reutilizamos la variable
        $value = trim( substr($value, 1) ); 

        //  Separamos el nombre del metodo de la cadena
        preg_match('/.*?\(/', $value, $methodNameSubtring);
        $methodNameSubtring = substr($methodNameSubtring[0], 0, -1);

        //  Separamos los parametros de la cadena
        preg_match('/\(.*?\)/', $value, $parametersSubstring);
        $parametersSubstring = $parametersSubstring[0];

        //  Separamos el tipo de retorno de la cadena
        preg_match('/\)\s?.*/', $value, $returnTypeSubstring);
        $returnTypeSubstring = $returnTypeSubstring[0];
        $returnTypeSubstring = str_replace(':', '', $returnTypeSubstring);
        $returnTypeSubstring = str_replace(')', '', $returnTypeSubstring);
        $returnTypeSubstring = trim($returnTypeSubstring);  

        //  Preparamos cadena de parámetros
        //  1) Removemos los paréntesis
        $parametersSubstring = substr($parametersSubstring, 1);
        $parametersSubstring = substr($parametersSubstring, 0, -1);

        //  2) Separamos los parámetros por ','
        $parameters = explode(',', $parametersSubstring);

        //  3)  Iterar sobre los parametros y construirlos como atributos
        $mxParameters = [];
        foreach($parameters as $parameter)
        {
            $ps = explode(':', $parameter);

            //  Contamos elementos de lista, deben ser exactamente dos de lo contrario
            //  se omite para ser parseado como un atributo
            if(count($ps) == 2 ) {
                $psName = trim($ps[0]);
                $psType = trim($ps[1]);

                $mxParameters[] = new MXAttribute(null, $psName, null, $psType);
            }

        }
        
        return [
            'methodNameSubtring' => $methodNameSubtring, 
            'encapsulationLevel' => $encapsulationLevel, 
            'mxParameters' => $mxParameters,
            'returnTypeSubstring' => $returnTypeSubstring
        ];
    }

    public function getMXRelationship($simpleXmlElement, $type) : MXRelationship {
        $id = $simpleXmlElement ['id']->__toString();
        $relationshipType = $type;

        //  Creamos variable de target
        $target = '';
        
        //  Si la relacion es de herencia
        if($type == 'inheritance')
        {
            //var_dump($simpleXmlElement); die;
            $target = !isset($simpleXmlElement['source']) ? '' : $simpleXmlElement['source']->__toString();
        }

        //  Si la relacion es de composicion
        if($type == 'composition')
        {
            $target = !isset($simpleXmlElement['target']) ? '' :  $simpleXmlElement['target']->__toString();
        }

        //  Si la relacion es de agregacion
        if($type == 'aggregation')
        {
            $target = !isset($simpleXmlElement['target']) ? '' :     $simpleXmlElement['target']->__toString();
        }

        //  Si la relacino es de implementacion
        if($type == 'implementation')
        {
            //var_dump($simpleXmlElement); die;
            $target = !isset($simpleXmlElement['target']) ? '' : $simpleXmlElement['target']->__toString();
        }
        return new MXRelationship( $id,$relationshipType, $target );
    }

    public function getMXInterface($simpleXmlElement)
    { 
        $idInterface = $simpleXmlElement['id']->__toString();
        
        //  Obtenemos los metadatos de la propiedad value
        $valueData = $simpleXmlElement['value']->__toString();
        //  Obtenemos lista de metadatos.
        //  Los metadatos obtenidos varía el número de
        //  separadores tiene un elemento Interface 

        if(strpos($valueData, '<hr size="1"/>'))
        {
            $metaData = explode('<hr size="1"/>', $valueData);
        }
        else
        {
            $metaData = explode('<hr size="1">', $valueData);
        }
        //  Obtenemos nombre de interfaz
        preg_match('/<b>.*?<\/b>/', $metaData[0], $interfaceName);

        $interfaceName = substr($interfaceName[0], 3, strlen($interfaceName[0]));  
        $interfaceName = substr($interfaceName, 0, -4);
        unset($metaData[0]);

        //  Verificamos el resto de metadatos
        $interfaceData = [];
        foreach($metaData as $mData)
        {
            $dom = new \DOMDocument();
            $dom->loadHTML($mData);
            $mDataText = $dom->textContent;
            $classification = MXHardClassifier::ClassifyAtributeOrMethod($mDataText);
            
            if($classification == 'attribute')
            {
                preg_match_all('/(\+|\-|\#)\s?\w*:\s?\w*/', $mDataText, $pregData);
                $interfaceData['attributes'] = $pregData[0];
            }

            if($classification == 'method')
            {
                preg_match_all('/(\+|\-|\#)(\s)?\w+\s?\((((\w?)*\s?\:?)*(\,|\s|\))*)+/', $mDataText, $pregData);
                $interfaceData['methods'] = $pregData[0];
            }
        }
        
        foreach($interfaceData['methods'] as $interfaceMethodString)
        {
            $result = $this->parseMXMethod($interfaceMethodString);
            $mxMethod = new MXMethod (
                null,
                $result['methodNameSubtring'], 
                $result['encapsulationLevel'], 
                $result['mxParameters'],
                $result['returnTypeSubstring']
            );
            $interfaceData['mxMethods'][] = $mxMethod;
        }
        return new MXInterface($idInterface, $interfaceName, $interfaceData['mxMethods']);
    }
}