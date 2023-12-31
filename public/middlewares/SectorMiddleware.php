<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class SectorMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();

        $sector = $parametros['sector'];
        $response = new Response();

        if ($sector === 'admin') {
            $respuesta = $handler->handle($request);
            $existingContent = json_decode($respuesta->getBody());
            $payload = json_encode($existingContent);
            $response->getBody()->write($payload);
        } else {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Acceso denegado, no es socio"));
            $response->getBody()->write($payload);
        }
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function authSocio(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();
        $data=LogInController::ObtenerData($request);
        $response = new Response();

        if ($data->sector == "socio") {
            $respuesta = $handler->handle($request);
            $existingContent = json_decode($respuesta->getBody());
            $payload = json_encode($existingContent);
            $response->getBody()->write($payload);
        } else {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Acceso denegado, no es socio"));
            $response->getBody()->write($payload);
        }
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function authMozo(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();
        $data=LogInController::ObtenerData($request);
        $response = new Response();  
        if ($data->sector == "mozo" || $data->sector == "socio")
        {
            $respuesta = $handler->handle($request);
            $existingContent = json_decode($respuesta->getBody());
            $payload = json_encode($existingContent);
            $response->getBody()->write($payload);
        } else {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Acceso denegado, no es mozo"));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function authBartender(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();
        $data=LogInController::ObtenerData($request);
        $response = new Response();

        if ($data->sector == "bartender" || $data->sector == "socio")
        {
            $respuesta = $handler->handle($request);
            $existingContent = json_decode($respuesta->getBody());
            $payload = json_encode($existingContent);
            $response->getBody()->write($payload);
        } else {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Acceso denegado, no es bartender"));
            $response->getBody()->write($payload);
        }
        
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function authCocinero(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();
        $data=LogInController::ObtenerData($request);
        $response = new Response();

        if ($data->sector == "cocinero" || $data->sector == "socio")
        {
            $respuesta = $handler->handle($request);
            $existingContent = json_decode($respuesta->getBody());
            $payload = json_encode($existingContent);
            $response->getBody()->write($payload);
        } else {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Acceso denegado, no es cocinero"));
            $response->getBody()->write($payload);
        }
        
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function authCervecero(Request $request, RequestHandler $handler): Response
    {   
        $parametros = $request->getParsedBody();
        $data=LogInController::ObtenerData($request);
        $response = new Response();

        if ($data->sector == "cervecero" || $data->sector == "socio")
        {
            $respuesta = $handler->handle($request);
            $existingContent = json_decode($respuesta->getBody());
            $payload = json_encode($existingContent);
            $response->getBody()->write($payload);
        } else {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Acceso denegado, no es cervecero"));
            $response->getBody()->write($payload);
        }
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}

