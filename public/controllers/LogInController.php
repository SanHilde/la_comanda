<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
class LogInController
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function Loggearse($request, $response, $args)
    {       
        // $parametros = $request->getQueryParams();
        $parametros = $request->getParsedBody();
        $nombre = $parametros['usuario']??null;
        $clave = $parametros['clave']??null;
        $response = new Response();
        $payload=null;
        if ($nombre !== null || $clave !== null)
        {
            $usuario = UsuarioController::obtenerUsuario($nombre);
            if ($usuario != false && $usuario->nombre === $nombre && $usuario->clave === $clave)
            {
                $datos = array('usuario' => $usuario->nombre, 'sector' => $usuario->sector, 'id' => $usuario->id);
                $token = AutentificadorJWT::CrearToken($datos);
                $payload = json_encode(array("mensaje" => "Bienvenido $nombre, tu sector es: $usuario->sector", 'jwt' => $token));
                // $response->getBody()->write($payload);
            } else {
                $payload = json_encode(array("mensaje" => "Usuario o clave erroneo"));
            }
        } else
        {
            $payload = json_encode(array("mensaje" => "Falta ingresar parametros en el logeo"));
        }
        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerData($request)
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $respuesta=AutentificadorJWT::ObtenerData($token);
        return  $respuesta;
    }
}