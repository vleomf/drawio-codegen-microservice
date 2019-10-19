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
        $this->reader->diagram = $this->Inflate($this->reader->diagram);
        var_dump($this->reader); die;
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
            $total = count( $this->reader->diagram[$page]->mxGraphModel->root->mxCell );
        }
        //  Si no existe la pagina en documento regresamos 0;
        catch(\Exception $e) { $total = 0; }
        return $total;
    }

    public function getMXCell(int $page, int $nodePosition) : IMXCell
    {
        return null;
    }

     /**
     * Metodo para inflar el diagrama mxfile, compresion gzip en base 64
     */
    private static function Inflate(string $xml)
    {
        return utf8_decode( urldecode( gzinflate( base64_decode( $xml ) ) ) ); 
    } 
}