<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Adapters\MXFile;

class RPCController extends Controller
{
    private $xmlFileStrategy;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){}

    public function health(Request $request) {
        return "ok";
    }    

    /**
     ** Controlador RPC, Aqui inician las llamadas
     ** JSON-RPC.
     *  @param Request peticionRPC 
     */
    public function call(Request $request) 
    {

        //  $request: 
        //      - lang: Que lenguaje quieres que genere
        //      - xml:  El XML a parsear

        if( strtolower($request->lang) == "php") {
            $this->GenerarPHP($request->xml);
        }
               
        return $request;
    }

    private function GenerarPHP( $xml ) {
        $this->xmlFileStrategy = new MXFile(new \SimpleXmlElement( $xml ));
        $totalPaginas = $this->xmlFileStrategy->totalPages();
        for($i = 0; $i<$totalPaginas; $i++){
            $totalNodosPorPagina = $this->xmlFileStrategy->totalNodesFromPage($i);
            print_r($totalNodosPorPagina); die;
        }
        
        print_r($totalPaginas); die;
    }
}
