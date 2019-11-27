<?php
namespace App\Adapters;

interface IOutputFileAdapter
{
    public function Write($mxNodes, $lang): string;
}