<?php
namespace App\Strategies;
use App\Factories\IMXCell;

//  AQUI DEBO USAR MXCell como clase a regresar al clasificar nodo 
//  a partir de un SingleXmlElement


interface IMXClassifier 
{
    public function classify($SimpleXmlNode) : IMXCell;
}
