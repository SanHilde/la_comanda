<?php
require_once './models/Log.php';

class logController{

    public function agregarLog ($user, $accion)
    {   
        $log = new Log();
        $log->user = $user;
        $log->fecha = date('Y-m-d H:i:s');
        $log->accion= $accion;

        return  $log->crearUno();
    }
    public function TraerTodos($request, $response, $args)
    {
        $usuario=LogInController::ObtenerData($request);
        $logController = new LogController();
        $logController->agregarLog($usuario->usuario,"Traer todas las encuestas");
        $lista = Log::obtenerTodos();
        $payload = json_encode(array("lista logs" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

}