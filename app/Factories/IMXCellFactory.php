<?php
namespace App\Factories;

interface IMXCellFactory 
{
    //  Debe retornar MXAttribute
    public function getMXAttribute() : \App\Factories\MXAtribute;

    //  Debe retornar MXClass
    public function getMXClass(): \App\Factories\MXClass;

    //  Deber retornar MXMethod
    public function getMXMethod(): \App\Factories\MXMethod;

    //  Deber retornar MXRelationship
    public function getMXRelationship(): \App\Factories\MXRelationship;

}