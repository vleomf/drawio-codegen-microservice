<?php
namespace App\Factories;

interface IMXCell 
{
    public function toString($language) : string;
}