<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Adapters\MXFile;
use App\Strategies\MXHardClassifier;
use App\Factories\MXCellFactory;

class RPCController extends Controller
{
    private $xmlFileAdapter;        //  Adaptador de XML
    private $classifier;            //  Clasificado a ocupar
    private $nodes;                 //  Nodos XML
    private $mxNodes;               //  Instancias MXCell
    private $mxCellFactory;         //  Fabrica de nodos MXCell

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        //  Inicializamos lista para guardar nodos que
        //  nos genera el Adaptador de archivo MXFile
        $this->nodes = [];
        $this->mxNodes = [];
        
        //  Definimos el clasificador a ocupar
        $this->classifier = new MXHardClassifier();

        //  Definimos la fabrica a ocupar
        $this->mxCellFactory = new MXCellFactory();
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
            //  Traemos la clasificacion del nodo XML
            $clasificacion = $this->classifier->classify($node);
            
            //  Obtenemos
            $id = isset($node['id']) ? $node['id'] : '0';  
            $parentID = isset( $node['parent'] ) ? $node['parent'] : '0';
            switch($clasificacion)
            {
                //  Traemos instancia de MXClass de factory
                case 'class':
                    $this->mxNodes[ strval($id) ] = $this->mxCellFactory->getMXClass($node);
                    break;
                case 'attribute':
                    $this->mxNodes[ strval( $parentID ) ]->insertAttribute( $this->mxCellFactory->getMXAttribute($node) );
                    break;
                case 'method':
                    $this->mxNodes[ strval ($parentID) ]->insertMethod( $this->mxCellFactory->getMXMethod($node) );
                    break;
                case 'inheritance':
                $this->mxNodes[strval($node ['source']) ] ->inserRelationshipType($this->mxCellFactory->getMXRelationship($node, 'inheritance' ));
                    break;
               /*  case 'composition':
                $this->mxNodes[strval($node ['']) ] ->inserRelationshipType($this->mxCellFactory->getMXRelationship($node, 'composition' ));
                    break;
                case 'aggregation':
                $this->mxNodes[strval($node ['']) ] ->inserRelationshipType($this->mxCellFactory->getMXRelationship($node, 'aggregation' ));
                    break; */
                
            }
            

            
            
            //var_dump($clasificacion);
            //var_dump($clasificacion); echo "<br />";        
            // switch($clasificacion) 
            // {
            //     case "global" :
            //         var_dump($node);
            //         //var_dump($node["value"], $node["style"]);
            //     break;
            // }
        }
        //var_dump($this->mxNodes ['VDleZtpJP4OSMNpLtCZ--15'] );
        //var_dump($this->mxNodes ['VDleZtpJP4OSMNpLtCZ--7']  );
        var_dump($this->mxNodes);
        die;

        
        print_r($totalPaginas); die;
    }
}
