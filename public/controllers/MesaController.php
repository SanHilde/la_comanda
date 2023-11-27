<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        if(isset($parametros['mozo']))
        {
            $mesa = new Mesa();
            $mesa->estado = "cerrada";
            $usuario = Usuario::obtenerUsuario($parametros['mozo']);
            if ($usuario != null && $usuario->sector == "mozo")
            {
                $mesa->mozo = $usuario->id;
                $mesa->pedido = "-";
                $mesa->crearUno();
                $payload = json_encode(array("mensaje" => "Mesa creada con éxito"));
            } else
            {
                $payload = json_encode(array("mensaje" => "El usuario ingresado no pertenece a un mozo"));
            }     
        } else{
            $payload = json_encode(array("mensaje" => "Falta ingresar parametros"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos Mesa por ID
        $id = $args['id']; // Suponiendo que el ID del Mesa se obtiene de los parámetros de la ruta
        // $parametros = $request->getParsedBody();
        // $id = $parametros['mesa'];
        $mesa = Mesa::obtenerMesa($id);
        if ($mesa) {
            $payload = json_encode($mesa);
           
        } else {
            $payload = json_encode(array("mensaje" => "Mesa no encontrada"));
        }
        
        $response->getBody()->write($payload);
        var_dump($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }    
    public static function Validar($id)
    {
        if(Mesa::obtenerMesa($id)!= null)
        {
            return true;
        } else
        {
            return false;
        }

    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesa" => $lista));
    
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $data=LogInController::ObtenerData($request);
        // $usuarioIngresado = $request->getAttribute('usuario');
        if(isset($parametros['estado']) && isset($parametros['mozo']) && isset($parametros['mesa']) && isset($parametros['pedido']))
        {
            $id = $parametros['mesa']; // Suponiendo que el ID del Mesa se pasa en el cuerpo de la solicitud
            $estado = $parametros['estado'];
            $mozo = $parametros['mozo'];
            $pedido = $parametros['pedido'];
            $usuario = Usuario::obtenerUsuario($mozo);
            $mesa = Mesa::obtenerMesa($id);
            if ($mesa) {
                if($estado=="con cliente esperando pedido" || $estado=="con cliente comiendo" || $estado=="con cliente pagando" || ($estado=="cerrada" && $data->sector=="socio"))
                {
                    if($usuario != false || $data->sector=="socio")
                    {
                        if( $data->sector=="socio"  || $usuario->sector=="mozo")
                        {
                            $mesa->estado = $estado;
                            $mesa->pedido = $pedido;
                            $mesa->mozo = $usuario->id ?? "-";
                            if($mesa->modificarMesa())
                            {
                                $payload = json_encode(array("mensaje" => "Mesa $id modificada con éxito, ahora en estado: '$estado' con el mozo: '$mozo' y el pedido: '$pedido'"));
                            } else
                            {
                                $payload = json_encode(array("mensaje" => "Error al modificar la mesa en la base de datos"));
                            }             
                        } else
                        {
                            $payload = json_encode(array("mensaje" => "El usuario ingresado no es mozo"));
                        }
                    } else
                    {
                        $payload = json_encode(array("mensaje" => "El mozo ingresado no existe"));
                    }  
                } else
                {
                    $payload = json_encode(array("mensaje" => "Estado de mesa no valido, solo puede ser: “con cliente esperando pedido”, ”con cliente comiendo”, “con cliente pagando”. Solo los socios pueden ingresar: “cerrada”."));
                }          
            } else {
                $payload = json_encode(array("mensaje" => "Mesa no encontrada"));
            }
        } else {
            $payload = json_encode(array("mensaje" => "Falta ingresar parametros"));
        } 
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
  

  public function BorrarUno($request, $response, $args)
  {
      $parametros = $request->getParsedBody();
      if(isset($parametros['id']))
      {
        $mesaId = $parametros['id']; // Suponiendo que el ID de la mesa se pasa en el cuerpo de la solicitud
        if(Mesa::borrarMesa($mesaId))
        {
            $payload = json_encode(array("mensaje" => "Mesa borrada con éxito"));
        }else
        {
            $payload = json_encode(array("mensaje" => "Error al intentar borrar la mesa"));
        }

      } else
      {
        $payload = json_encode(array("mensaje" => "Falta ingresar id de mesa"));
      }

  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  
  
}