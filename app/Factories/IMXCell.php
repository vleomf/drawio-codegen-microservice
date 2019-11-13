<?php
namespace App\Factories;

interface IMXCell 
{
    public function toString($language) : string;

    public function setRelationshipNodesReferences($mxNodes) : void;
}