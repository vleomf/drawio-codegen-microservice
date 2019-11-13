<?php
namespace App\Adapters;

class OutputFile implements IOutputFileAdapter
{
    private $path;
    private $language;
    public function __construct($path, $language)
    {
        $this->path = $path;
    }

    public function Write($mxNodes) : void  
    {
        //var_dump("aqui", $mxNodes); die;
        foreach($mxNodes as $node)
        {
            //  Pasamos referencia de nodos para 
            //  obtener los datos de las relaciones
            //  entre clases e interfaces
            $node->setRelationshipNodesReferences($mxNodes);
            $stringContent = $node->toString('php');
            var_dump($stringContent);
        }
        die;    
    }
}