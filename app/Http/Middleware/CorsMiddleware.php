<?php
/**
* Location: /app/Http/Middleware
*/
namespace App\Http\Middleware;
use Closure;
class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        //  Cabeceras que permiten el acceso CORS
        //  La cabecera Content-Disposition es vital para
        //  retornar el nombre del archivo en los metadatos
        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With, Content-Disposition'
        ];
        if ($request->isMethod('OPTIONS'))
        {
            return response()->json(["method" => "OPTIONS"], 200, $headers);
        }
        $response = $next($request);
        foreach($headers as $key => $value)
        {
            $response->headers->set($key, $value);
        }
        return $response;
    }
}