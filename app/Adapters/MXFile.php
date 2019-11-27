<?php

namespace App\Adapters;

/**
 ** Implementacion de IMXFileAdapter
 *  Permite leer los documentos xml (mxFile)
 *  generados por draw.io
 */
class MXFile implements IMXFileAdapter
{
    private $reader;

    /**
     ** Constructor
     *  Inicializa la instancia del adaptador
     *  @param \SimpleXmlElement lector
     */
    public function __construct(\SimpleXmlElement $reader)
    {
        $this->reader = $reader;
    }

    /**
     ** Calcula el total de paginas en documento mx
     *  @return int totalPaginas 
     */
    public function totalPages() : int 
    {
        //var_dump("MXFile.totalPages()");
        //var_dump($this->reader);
        //die;

        $this->reader->diagram = $this->Inflate($this->reader->diagram);
        //  var_dump($this->reader); die;
        return count($this->reader->diagram);
    }

    /**
     ** Calcula el total de nodos en documento mx
     *  @param  int pagina
     *  @return int total de nodos 
     */
    public function totalNodesFromPage(int $page) : int
    {
        //  Validacion de pagina en numero positivo
        if($page < 0 ) {
            return 0;
        }
        try
        {
            $innerXmlElement = new \SimpleXmlElement($this->reader->diagram[$page]);
            $total = count( $innerXmlElement->root->mxCell );
        }
        //  Si no existe la pagina en documento regresamos 0;
        catch(\Exception $e) 
        { 
            $total = 0; 
        }
        return $total;
    }

    public function getMXCell(int $page, int $nodePosition) : \SimpleXmlElement
    {
        //  Validacion de pagina en numero positivo
        if($page < 0 ) {
            //return null;
        }
        try
        {
            $innerXmlElement = new \SimpleXmlElement($this->reader->diagram[$page]);
            $node = $innerXmlElement->root->mxCell[$nodePosition];
            //var_dump($node); die;
        }
        //  Si no existe la pagina en documento regresamos 0;
        catch(\Exception $e) 
        { 
            //  ESTO NO ES CORRECTO, SOLO POR PRUEBAS
            var_dump("MXFile.getMXCell Exception");
            //var_dump($e);
        }
        return $node;
    }

     /**
     * Metodo para inflar el diagrama mxfile, compresion gzip en base 64
     */
    private static function Inflate(string $xml)
    {
        //var_dump("\nMXFile.Inflate");
        //var_dump($xml); 
        //die;
        try
        {
            $inflated = utf8_decode( urldecode( gzinflate( base64_decode( $xml ) ) ) );
            return $inflated; 
        }
        catch(\Exception $e)
        {
            var_dump("MXFile.Inflate exception");
            //var_dump($e); 
            //die;
            return "";
        }
        
    } 
}