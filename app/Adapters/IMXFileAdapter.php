<?php

namespace App\Adapters;

/**
 ** Interfaz para lectura de archivos 'mxDiagram' (drawio) 
 */
interface IMXFileAdapter
{
    /**
     ** Metodo que calcula el total de paginas en documento 'mx; 
     *  @return int numero de paginas
     */
    public function totalPages() : int;

    /**
     ** Metodo que calcula el total de nodos en una pagina del documento
     *  @param  int pagina del documento
     *  @return int numero de nodos 
     */
    public function totalNodesFromPage(int $page) : int;

    /**
     ** Metodo para obtener nodo del documento por pagina
     *  @param  int     pagina del documento
     *  @param  int     posicion del nodo
     *  @return \SimpleXmlElement  nodo   
     */
    public function getMXCell(int $page, int $nodePosition) : \SimpleXmlElement;
}