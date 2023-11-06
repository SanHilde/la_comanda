<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        
        $pedido = new Pedido();
        $pedido->codigoPedido = $parametros['codigoPedido'];
        $pedido->producto = $parametros['producto'];
        $pedido->cantidad = $parametros['cantidad'];
        $pedido->mesa = $parametros['mesa'];
        $pedido->mozo = $parametros['mozo'];
        $pedido->fecha = $parametros['fecha']; // Asumiendo que la fecha y hora se proporciona en el formato adecuado
        $pedido->estado = $parametros['estado'];
        
        $pedido->crearPedido();
        
        $payload = json_encode(array("mensaje" => "Pedido creado con éxito"));
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    
  

    public function TraerUno($request, $response, $args)
    {
        // Buscamos pedido por id
        $id = $args['id']; // Suponiendo que el ID del pedido se obtiene de los parámetros de la ruta
        $pedido = Pedido::obtenerPedido($id);
        
        if ($pedido) {
            $payload = json_encode($pedido);
        } else {
            $payload = json_encode(array("mensaje" => "Pedido no encontrado"));
        }
    
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
  

  public function TraerTodos($request, $response, $args)
  {
      $lista = Pedido::obtenerTodos();
      $payload = json_encode($lista);
  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  
    
  public function ModificarUno($request, $response, $args)
  {
      $parametros = $request->getParsedBody();
      $id = $args['id']; // Suponiendo que el ID del pedido se obtiene de los parámetros de la ruta
  
      // Obtén el pedido que deseas modificar por su ID
      $pedido = Pedido::obtenerPedido($id);
  
      if ($pedido) {
          // Actualiza los atributos del pedido con los nuevos valores
          $pedido->codigoPedido = $parametros['codigoPedido'];
          $pedido->producto = $parametros['producto'];
          $pedido->cantidad = $parametros['cantidad'];
          $pedido->mesa = $parametros['mesa'];
          $pedido->mozo = $parametros['mozo'];
          $pedido->fecha = $parametros['fecha'];
          $pedido->estado = $parametros['estado'];
  
          // Llama al método para modificar el pedido en la base de datos
          $pedido->modificarPedido();
  
          $payload = json_encode(array("mensaje" => "Pedido modificado con éxito"));
      } else {
          $payload = json_encode(array("mensaje" => "Pedido no encontrado"));
      }
  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  
  

  public function BorrarUno($request, $response, $args)
  {
      $parametros = $request->getParsedBody();
  
      $pedidoId = $parametros['pedidoId']; // Suponiendo que el ID del pedido se pasa en el cuerpo de la solicitud
  
      // Llama al método para borrar el pedido por su ID
      Pedido::borrarPedido($pedidoId);
  
      $payload = json_encode(array("mensaje" => "pedido borrado con éxito"));
  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  
}