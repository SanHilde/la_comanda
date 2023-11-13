<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class LoggerMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   

        // $before = date('Y-m-d H:i:s');
        
        // $response = $handler->handle($request);
        // $existingContent = json_decode($response->getBody());
    
        // $response = new Response();
        // $existingContent->fechaAntes = $before;
        // $existingContent->fechaDespues = date('Y-m-d H:i:s');
        
        // $payload = json_encode($existingContent);

        // $response->getBody()->write($payload);
        // return $response->withHeader('Content-Type', 'application/json');
        
        // $parametros = $request->getQueryParams();
        $parametros = $request->getParsedBody();

        $nombre = $parametros['usuario']??null;
        $clave = $parametros['clave']??null;
        $response = new Response();
        if ($nombre !== null || $clave !== null)
        {
            $usuario = UsuarioController::obtenerUsuario($nombre);
            if ($usuario != false && $usuario->nombre === $nombre) {
                if ($usuario->clave === $clave)
                {
                    $request = $request->withAttribute('usuario', $usuario);
                    $respuesta = $handler->handle($request);
                    $existingContent = json_decode($respuesta->getBody());
                    $payload = json_encode($existingContent);
                    $response->getBody()->write($payload);
                } else
                {
                    $payload = json_encode(array("mensaje" => "Clave erronea"));
                    $response->getBody()->write($payload);
                }
            } else {
                $payload = json_encode(array("mensaje" => "Usuario no registrado"));
                $response->getBody()->write($payload);
            }
        } else
        {
            $payload = json_encode(array("mensaje" => "Falta ingresar parametros en el logeo"));
            $response->getBody()->write($payload);
        }
       
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}