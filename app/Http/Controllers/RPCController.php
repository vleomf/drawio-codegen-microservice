<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Adapters\MXFile;
use App\Strategies\MXHardClassifier;

class RPCController extends Controller
{
    private $xmlFileAdapter;
    private $nodes;
    private $classifier;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        //  Inicializamos lista para guardar nodos que
        //  nos genera el Adaptador de archivo MXFile
        $this->nodes = [];
        //  Definimos el clasificador a ocupar
        $this->classifier = new MXHardClassifier();
    }

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
        //  Cargamos cadena XML en el Adaptador
        $this->xmlFileAdapter = new MXFile(new \SimpleXmlElement( $xml ));
        
        //  Obtenemos el total de paginas dentro del XML 
        //  Nota: El diagrama de Drawoio genera los xml por pagina
        //  se debe verificar que exista pagina alguna que procesar.
        $totalPaginas = $this->xmlFileAdapter->totalPages();
        
        //  Iteramos las paginas del diagrama y obtenemos el total de nodos
        //  por pagina. Estos nodos procesaran para crear nodos usando la fabrica
        for($i = 0; $i<$totalPaginas; $i++) 
        {
            //  Obtenemos el total de nodos por pagina.
            $totalNodosPorPagina = $this->xmlFileAdapter->totalNodesFromPage($i);
            
            //  Iteramos nodo por nodo
            for( $ii = 0; $ii < $totalNodosPorPagina; $ii++ )
            {
                //  Insertamos elementos MXCell en la lista de nodos
                $this->nodes[] = $this->xmlFileAdapter->getMXCell( $i, $ii );
            }    
        }

        //  Hay que clasificar los nodos y generar los objetos desde la fabrica
        //  Cada objeto puede ser, o clase, o atributo, o relacion entre clases.. etc
        //  Todo esto en funcion de los diagramas de clase UML.

        //var_dump($this->nodes); die;
        foreach($this->nodes as $node) 
        {
            $clasificacion = $this->classifier->classify($node);

            var_dump($node['id'], $node['value']);
            var_dump($clasificacion); echo "<br />";
            // switch($clasificacion) 
            // {
            //     case "global" :
            //         var_dump($node);
            //         //var_dump($node["value"], $node["style"]);
            //     break;
            // }
        }
        
        die;

        
        print_r($totalPaginas); die;
    }
}
