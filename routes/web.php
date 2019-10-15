<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//  Esta es una ruta para checar que el servicio esta activo
$router->get( '/', 'RPCController@health');

//  Esta es la ruta de RPC
$router->post('/', 'RPCController@call'  );
