<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = $request->getAttribute('usuario');
        $codigo = $parametros['codigoPedido'] ?? null;
        // var_dump($response);
        // $args->withAttribute('descripcion',$parametros['producto']);
        // $productoController=new ProductoController();
        // $producto= json_decode($productoController->TraerUno($request, $response, $args)->getBody(),true);
        // $mesaController=new MesaController();
        // $mesa = json_decode($mesaController->TraerUno($request, $response, $args)->getBody(),true);
        // var_dump($mesa);
        if(ProductoController::Validar($parametros['producto']))
        {
            if (MesaController::Validar($parametros['mesa']))
            {
                $producto = Producto::obtenerProducto($parametros['producto']);
                $mesaController=new MesaController();
                $pedido = new Pedido();
                if( $codigo  == null)
                {
                    $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $longitud = 5;
                    $codigoGenerado = '';
                    for ($i = 0; $i < $longitud; $i++) {
                        $codigoGenerado .= $caracteres[rand(0, strlen($caracteres) - 1)];
                    }
                    $codigo=$codigoGenerado; 
                }
                $pedido->codigoPedido = $codigo;
                // echo($producto->id);
                $pedido->producto = $producto->id;
                $pedido->cantidad = $parametros['cantidad'];
                $pedido->mesa = $parametros['mesa'];
                $pedido->mozo = $usuario->id;
                $pedido->fecha = date('Y-m-d H:i:s');
                $pedido->estado = "pendiente";
                $pedido->sector = $producto->sector;
                // $requestEditado = $request->withParsedBody([
                //     'mozo' => $usuario->nombre,
                //     'estado' => 'ocupada',
                //     'mesa' => $parametros['mesa']
                // ]);
                
                // $mesaController->ModificarUno($requestEditado, $response, $args);
                
                echo($pedido->crearPedido());
                var_dump($pedido);                
                $payload = json_encode(array("mensaje" => "Pedido creado con éxito. Codigo de pedido: $codigo"));
            } else
            {
                $payload = json_encode(array("mensaje" => "Mesa inexistente"));
            }

        } else
        {
            $payload = json_encode(array("mensaje" => "Producto inexistente"));
        }
        
        
        $response->getBody()->write($payload);
        // echo $payload;
        // var_dump($response);

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
    $parametros = $request->getQueryParams();
    $usuario = $request->getAttribute('usuario');
    $estado = $parametros['estado'] ?? null;
    $sector = $usuario->sector;
    if ($usuario->sector== "socio" || $usuario->sector == null)
    {
        $sector= null;
    } 
    $lista = Pedido::obtenerTodos($estado,$sector);
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
          $pedido->sector = $parametros['sector'];
  
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