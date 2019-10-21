<?php
namespace App\Strategies;
use App\Factories\IMXCell;

//  AQUI DEBO USAR MXCell como clase a regresar al clasificar nodo 
//  a partir de un SingleXmlElement


interface IMXClassifier 
{
    /**
     *   @param \SimpleXmlElement Es el nodo que retorna MXFile
     */
    public function classify(& $SimpleXmlNode) : string;
}
