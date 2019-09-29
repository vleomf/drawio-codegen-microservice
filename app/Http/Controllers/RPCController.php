<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Adapters\MXFile;

class RPCController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){}

    /**
     ** Controlador RPC, Aqui inician las llamadas
     ** JSON-RPC.
     *  @param Request peticionRPC 
     */
    public function call(Request $request) 
    {
        $fileAdapter = new MXFile(new \SimpleXmlElement( $request->xml ));
        $fileAdapter->totalPages();
        $fileAdapter->totalNodesFromPage(0);
        return "Hola index";
    }
}
