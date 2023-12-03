<?php
require_once './models/Encuesta.php';
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class EncuestaController extends Encuesta implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        if (isset($parametros['pedido'], $parametros['comentarios'], $parametros['mesa'], $parametros['restaurante'], $parametros['mozo'], $parametros['cocinero']))
        {
            if(self::ValidarPuntaje($parametros['mesa']) && self::ValidarPuntaje($parametros['restaurante']) && self::ValidarPuntaje($parametros['mozo']) && self::ValidarPuntaje($parametros['cocinero'])  )
            {
                $pedido = Pedido::obtenerPedidoPorCodigo($parametros['pedido']);
                $encuesta = Encuesta::obtenerEncuesta($parametros['pedido']);
                if ($pedido) 
                { 
                    if(!$encuesta)
                    {
                        $encuesta = new Encuesta();
                        $encuesta->mesa = $parametros['mesa'];
                        $encuesta->pedido = $parametros['pedido'];
                        $encuesta->restaurante = $parametros['restaurante'];
                        $encuesta->mozo = $parametros['mozo'];
                        $encuesta->cocinero = $parametros['cocinero'];
                        $encuesta->comentarios = $parametros['comentarios'];
                        
                        $encuesta->crearUno();
                        $payload = json_encode(array("mensaje" => "Encuesta creada con éxito"));
                    } else
                    {
                        $payload = json_encode(array("mensaje" => "La encuesta ya existe"));
                    }

                } else
                {
                    $payload = json_encode(array("mensaje" => "El pedido no existe"));
                }
            } else
            {
                $payload = json_encode(array("mensaje" => "No ingreso un numero del 1 al 10 en uno de los puntajes"));
            }              
        } else {
            $payload = json_encode(array("mensaje" => "Faltan ingresar parámetros"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $usuario=LogInController::ObtenerData($request);
        $logController = new LogController();
        $logController->agregarLog($usuario->usuario,"Traer encuesta");
        $pedido = $args['pedido'];
        $encuesta = Encuesta::obtenerEncuesta($pedido);

        if ($encuesta) {
            $payload = json_encode($encuesta);
        } else {
            $payload = json_encode(array("mensaje" => "Encuesta no encontrada"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $usuario=LogInController::ObtenerData($request);
        $logController = new LogController();
        $logController->agregarLog($usuario->usuario,"Traer todas las encuestas");
        $lista = Encuesta::obtenerTodos();
        $payload = json_encode(array("listaEncuesta" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerMejoresComentarios($request, $response, $args)
    { 
        $usuario=LogInController::ObtenerData($request);
        $logController = new LogController();
        $logController->agregarLog($usuario->usuario,"Traer mejores comentarios");
        $cantComentarios = $args['cantComentarios'];
        $lista = Encuesta::obtenerTodos();
        $mejoresComentarios = [];

        foreach ($lista as $encuesta) {
            $puntaje = $encuesta->mesa + $encuesta->restaurante + $encuesta->mozo + $encuesta->cocinero;
            $mejorComentario = [
                'puntaje' => $puntaje,
                'comentario' => $encuesta->comentarios
            ];
        
            $mejoresComentarios[] = $mejorComentario;
        }
        usort($mejoresComentarios, function ($a, $b) {
            return $b['puntaje'] - $a['puntaje'];
        });

        array_splice($mejoresComentarios, $cantComentarios);
        if (count($mejoresComentarios) != 0) 
        {
            $payload = json_encode(array("Mejores $cantComentarios comentarios " => $mejoresComentarios));
        } else
        {
            $payload = json_encode(array("mensaje" => "No hay un comentarios para mostrar"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerMesaMasUsada($request, $response, $args)
    { 
        $usuario = LogInController::ObtenerData($request);
        $logController = new LogController();
        $logController->agregarLog($usuario->usuario, "Traer mesa mas usada");
    
        $lista = Encuesta::obtenerTodos();
        $mesasUsadas = [];
    
        foreach ($lista as $encuesta) {
            $pedido = Pedido::obtenerPedidoPorCodigo($encuesta->pedido);
    
            if (!isset($mesasUsadas[$pedido->mesa])) {
                $mesasUsadas[$pedido->mesa] = 0;
            }
    
            $mesasUsadas[$pedido->mesa]++;
        }
    
        arsort($mesasUsadas);
        if (count($mesasUsadas) != 0) {
            // Obtener el primer elemento del array ordenado
            $mesaMasUsada = key($mesasUsadas);
            $usos = reset($mesasUsadas);
    
            $payload = json_encode(array("Mesa mas usada" => "$mesaMasUsada con $usos usos"));
        } else {
            $payload = json_encode(array("mensaje" => "No hay un comentarios para mostrar"));
        }
    
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    

    public function ValidarPuntaje($valor)
    {
        $respuesta=null;
        if(isset($valor) && is_numeric($valor) && $valor >= 1 && $valor <= 10)
        {
            $respuesta = $valor;
        }
        return $respuesta;
    }
    public function ModificarUno($request, $response, $args)
    {
        $usuario=LogInController::ObtenerData($request);
        $logController = new LogController();
        $logController->agregarLog($usuario->usuario,"Modificar encuesta");
        $parametros = $request->getParsedBody();
        if (isset($parametros['pedido']))
        {
            $encuesta = Encuesta::obtenerEncuesta($parametros['pedido']);
            if ($encuesta) 
            {
                $encuesta->mesa = self::ValidarPuntaje($parametros['mesa']) ?? $encuesta->mesa;
                $encuesta->restaurante = self::ValidarPuntaje($parametros['restaurante']) ?? $encuesta->restaurante;
                $encuesta->mozo = self::ValidarPuntaje($parametros['mozo']) ?? $encuesta->mozo;
                $encuesta->cocinero = self::ValidarPuntaje($parametros['cocinero']) ?? $encuesta->cocinero;
                $encuesta->comentarios = $parametros['comentarios'] ?? $encuesta->comentarios;
                

                if ($encuesta->modificarEncuesta()) {
                    $response->getBody()->write(json_encode(array("mensaje" => "Encuesta modificada con éxito")));
                } else {
                    $response->getBody()->write(json_encode(array("mensaje" => "Error al modificar la encuesta en la base de datos")));
                }
            } else {
                $response->getBody()->write(json_encode(array("mensaje" => "Encuesta no encontrada")));
            }
        } else {
            $response->getBody()->write(json_encode(array("mensaje" => "Faltan ingresar parámetros")));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }


    public function BorrarUno($request, $response, $args)
    {
        $usuario=LogInController::ObtenerData($request);
        $logController = new LogController();
        $logController->agregarLog($usuario->usuario,"Borrar encuesta");
        $parametros = $request->getParsedBody();
        if (isset($parametros['pedido'])) {
            $pedido = $parametros['pedido']; 
            if (Encuesta::borrarEncuesta($pedido)) {
                $payload = json_encode(array("mensaje" => "Encuesta borrada con éxito"));
            } else {
                $payload = json_encode(array("mensaje" => "Error al intentar borrar la encuesta"));
            }
        } else {
            $payload = json_encode(array("mensaje" => "Falta ingresar id de encuesta"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
}

}
