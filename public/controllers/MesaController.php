<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        
        $mesa = new Mesa();
        $mesa->estado = $parametros['estado'];
        $mesa->mozo = $parametros['mozo'];
    
        $mesa->crearMesa();
    
        $payload = json_encode(array("mensaje" => "Mesa creado con éxito"));
    
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
  

    public function TraerUno($request, $response, $args)
    {
        // Buscamos Mesa por ID
        $id = $args['id']; // Suponiendo que el ID del Mesa se obtiene de los parámetros de la ruta
        $mesa = Mesa::obtenerMesa($id);
        
        if ($mesa) {
            $payload = json_encode($mesa);
        } else {
            $payload = json_encode(array("mensaje" => "Mesa no encontrado"));
        }
    
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
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
    
        $id = $parametros['id']; // Suponiendo que el ID del Mesa se pasa en el cuerpo de la solicitud
        $descripcion = $parametros['descripcion'];
        $precio = $parametros['precio'];
        $tipo = $parametros['tipo'];
    
        // Obtén el Mesa que deseas modificar por su ID
        $mesa = Mesa::obtenerMesa($id);
    
        if ($mesa) {
            // Actualiza los atributos del Mesa con los nuevos valores
            $mesa->descripcion = $descripcion;
            $mesa->precio = $precio;
            $mesa->tipo = $tipo;
    
            // Llama al método para modificar el Mesa en la base de datos
            $mesa->modificarMesa();
    
            $payload = json_encode(array("mensaje" => "Mesa modificado con éxito"));
        } else {
            $payload = json_encode(array("mensaje" => "Mesa no encontrado"));
        }
    
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
  

  public function BorrarUno($request, $response, $args)
  {
      $parametros = $request->getParsedBody();
  
      $mesaId = $parametros['mesaId']; // Suponiendo que el ID de la mesa se pasa en el cuerpo de la solicitud
  
      // Llama al método para borrar la mesa por su ID
      Mesa::borrarMesa($mesaId);
  
      $payload = json_encode(array("mensaje" => "Mesa borrada con éxito"));
  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  
  
}