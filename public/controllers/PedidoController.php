<?php
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        
        $parametros = $request->getParsedBody();
        $usuario=LogInController::ObtenerData($request);
        $logController = new LogController();
        $logController->agregarLog($usuario->usuario,"Cargar un pedido");
        //$usuario = $request->getAttribute('usuario');
        $codigo = $parametros['codigoPedido'] ?? null;
        if(isset($parametros['cantidad']) && isset($parametros['producto']) && isset($parametros['mesa']))
        {
            if(ProductoController::Validar($parametros['producto']))
            {
                if (MesaController::Validar($parametros['mesa']))
                {
                    $producto = Producto::obtenerProducto($parametros['producto']);
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
                    $pedido->producto = $producto->id;
                    $pedido->cantidad = $parametros['cantidad'];     
                    $pedido->mesa = $parametros['mesa'];
                    $pedido->mozo = $usuario->id;
                    $pedido->fecha = date('Y-m-d H:i:s');
                    $pedido->estado = "pendiente";
                    $pedido->sector = $producto->sector; 
                    $pedido->tiempoPreparacion = 0; 
                    $pedido->tiempoEntrega = 0;    
                    if($pedido->crearUno())
                    {
                        $payload = json_encode(array("mensaje" => "Pedido creado con éxito. Codigo de pedido: $codigo"));
                    } else
                    {
                        $payload = json_encode(array("mensaje" => "Hubo un error en la base de datos al crear el pedido"));
                    }               
                    
                } else
                {
                    $payload = json_encode(array("mensaje" => "Mesa inexistente"));
                }
    
            } else
            {
                $payload = json_encode(array("mensaje" => "Producto inexistente"));
            }      
        } else
        {
            $payload = json_encode(array("mensaje" => "Falta ingresar parametros"));
        }
 
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function TraerUno($request, $response, $args)
    {
        $usuario=LogInController::ObtenerData($request);
        $logController = new LogController();
        $logController->agregarLog($usuario->usuario,"Traer un pedido");
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
    $usuario=LogInController::ObtenerData($request);
    $logController = new LogController();
    $logController->agregarLog($usuario->usuario,"Traer todos los pedidos");
    $parametros = $request->getQueryParams();
    //$usuario = $request->getAttribute('usuario');
    $estado = $parametros['estado'] ?? null;
    $sector = $usuario->sector;
    if ($usuario->sector== "socio" || $usuario->sector== "mozo" || $usuario->sector == null)
    {
        $sector= null;
    } 
    $lista = Pedido::obtenerTodos($estado,$sector);

    $payload = json_encode($lista);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerVendidos($request, $response, $args)
  {
      $parametros = $request->getQueryParams();
      $listaProductos = Producto::obtenerTodos();
      $listaDePedidos = Pedido::obtenerTodos(null, null);
      $usuario=LogInController::ObtenerData($request);
      $logController = new LogController();
      $logController->agregarLog($usuario->usuario,"Traer pedidos vendidos");
      $ventaDeProductos = array();
  
      foreach ($listaProductos as $producto) {
          $productoVendido = [
              'descripcion' => $producto->descripcion,
              'cantidad' => 0
          ];
          $ventaDeProductos[$producto->descripcion] = $productoVendido;
      }
  
      foreach ($listaDePedidos as $pedido) {
          $productoDescripcion = $pedido->producto;
          $ventaDeProductos[$productoDescripcion]['cantidad'] += $pedido->cantidad;
      }
  
      usort($ventaDeProductos, function ($a, $b) {
          return $b['cantidad'] - $a['cantidad'];
      });
      if ($parametros["vendido"] == "mas vendido") {
          $producto = $ventaDeProductos[0]['descripcion'];
          $cantidad = $ventaDeProductos[0]['cantidad'];
          $payload = json_encode(array("mensaje" => "El producto más vendido es $producto con $cantidad"));
      } else {
          $ultimoIndice = count($ventaDeProductos) - 1;
          $producto = $ventaDeProductos[$ultimoIndice]['descripcion'];
          $cantidad = $ventaDeProductos[$ultimoIndice]['cantidad'];
          $payload = json_encode(array("mensaje" => "El producto menos vendido es $producto con $cantidad"));
      }
  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  
  

  public function TraerTodosEnTiempo($request, $response, $args)
  {
    $parametros = $request->getQueryParams();
    $responeTraer = clone $response;
    $usuario=LogInController::ObtenerData($request);
    $logController = new LogController();
    $logController->agregarLog($usuario->usuario,"Traer todos los pedidos en tiempo");
    self::TraerTodos($request, $responeTraer, $args);
    $lista = json_decode($responeTraer->getBody());
    $listaEnTiempo= array();
    if(isset ($parametros['tiempo']))
    {

        if ($parametros['tiempo']=="atrasados")
        {
            foreach($lista  as $pedido)
            {
                if ($pedido->tiempoEntrega <0)
                {
                    array_push($listaEnTiempo,$pedido);
                }
            }
        } else
        {
            foreach($lista  as $pedido)
            {
                if ($pedido->tiempoEntrega>=0)
                {
                    array_push($listaEnTiempo,$pedido);
                }
            }
        }
    } else
    {
        $payload = json_encode(array("mensaje" => "Falta ingresar parametros"));
    }
    $payload = json_encode($listaEnTiempo);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

    public function CalcularCuenta($request, $response, $args)
    {
        $usuario=LogInController::ObtenerData($request);
        $logController = new LogController();
        $logController->agregarLog($usuario->usuario,"Calcular cuenta");
       // $parametros = $request->getQueryParams(); 
        $parametros= $request->getParsedBody(); 
        if (isset($parametros['codigo']) && isset($parametros['mesa']))
        {
            $id = $parametros['codigo'];
            $mesa = $parametros['mesa'];
            $lista = Pedido::obtenerTodos(null,null);
            $listaDeCodigo = array();
            $contador=0;
            foreach($lista as $pedido)
            {
                if ($pedido->codigoPedido==$id && $pedido->mesa == $mesa)
                {
                    $producto = Producto::obtenerProducto($pedido->producto);
                    $contador = $contador + ($producto->precio *$pedido->cantidad);
                }      
            }
            $respuesta = "El total a cobrar es $$contador";
            $payload = json_encode(array("mensaje" => $respuesta));
        } else
        {
            $payload = json_encode(array("mensaje" => "Falta ingresar parametros"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
      }
  
  public function TraerPorCodigo($request, $response, $args)
  {

    $parametros = $request->getQueryParams();  
    if (isset($parametros['codigo']) && isset($parametros['mesa']))
    {
        $id = $parametros['codigo'];
        $mesa = $parametros['mesa'];
        $lista = Pedido::obtenerTodos(null,null);
        $listaDeCodigo = array();
        foreach($lista as $pedido)
        {
            if ($pedido->codigoPedido==$id && $pedido->mesa == $mesa)
            {
                array_push($listaDeCodigo, $pedido);
            }      
        }
        if(count($listaDeCodigo)>0)
        {
            $respuesta="Codigo: $id";
            $tiempoMaximo=0;
            $tiempoFaltante=0;
            foreach ($listaDeCodigo as $pedido) {
                $producto = Producto::obtenerProducto($pedido->producto);
                $respuesta .= ", pedido: $producto->descripcion, cantidad: $pedido->cantidad";
            
                if ($pedido->tiempoPreparacion > $tiempoMaximo) {
                    $pedido->fecha = new DateTime($pedido->fecha);
                    $fechaConvertida = clone $pedido->fecha;
                    $fechaConvertida->add(new DateInterval('PT' . $pedido->tiempoPreparacion . 'M'));
                    $tiempoActual = new DateTime();
                    $tiempoRestante = max(0, ($fechaConvertida->getTimestamp() - $tiempoActual->getTimestamp()) / 60);
                    $tiempoRestante = round($tiempoRestante);
                    $tiempoMaximo = $pedido->tiempoPreparacion;
                    $tiempoFaltante = $tiempoRestante;
                }
            }
            $respuesta = "$respuesta,tiempo para que este listo: $tiempoFaltante minutos";
            $payload = json_encode(array("mensaje" => $respuesta));
        } else
        {
            $payload = json_encode(array("mensaje" => "No hay un codigo vinculado a esa mesa"));
        }

    } else
    {
        $payload = json_encode(array("mensaje" => "Falta ingresar parametros"));
    }
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }
  
  public function ModificarUno($request, $response, $args)
  {
    $usuario=LogInController::ObtenerData($request);
    $logController = new LogController();
    $logController->agregarLog($usuario->usuario,"Modificar un pedido");
    $bandera = 0;
      $parametros = $request->getParsedBody();  
      if (isset($parametros['id']))
      {
        $id = $parametros['id'];
        $pedido = Pedido::obtenerPedido($id);
        $usuario=LogInController::ObtenerData($request);
        //$usuario = $request->getAttribute('usuario');

        if ($pedido) 
        {
            if ($usuario->sector == $pedido->sector || $usuario->sector == "socio" || $usuario->sector== "mozo")
            {
               
                if($usuario->sector == "socio" || $usuario->sector== "mozo")
                {
                    if(isset($parametros['producto']))
                    {
                        if(ProductoController::Validar($parametros['producto']))
                        {
                            $producto = Producto::obtenerProducto($parametros['producto']);
                            $pedido->producto = $producto->id ?? $pedido->producto;
                        } else
                        {
                            $payload = json_encode(array("mensaje" => "Producto inexistente"));
                            $bandera=1;
                        } 
                    }
                    if (isset($parametros['mesa']))
                    {
                        if (MesaController::Validar($parametros['mesa']))
                        {
                            $pedido->mesa = $parametros['mesa'] ?? $pedido->mesa;
                        } else
                        {
                            $payload = json_encode(array("mensaje" => "Mesa inexistente"));
                            $bandera=1;
                        }
                    }
                    $pedido->id = $id;
                    $pedido->codigoPedido = $parametros['codigoPedido'] ?? $pedido->codigoPedido;                
                    $pedido->cantidad = $parametros['cantidad']?? $pedido->cantidad;
                    if (isset($parametros['mozo']))
                    {
                        $mozo=Usuario::obtenerUsuario($parametros['mozo']);
                        var_dump($mozo);
                        if($mozo!=null && $mozo->sector == "mozo")
                        {
                            $pedido->mozo = $mozo->id ?? $pedido->mozo;
                        } else
                        {
                            $payload = json_encode(array("mensaje" => "Mozo inexistente"));
                            $bandera=1;
                        }
                    }
                }
                if ($usuario->sector == $pedido->sector )
                {
                    $pedido->tiempoPreparacion = $parametros['tiempoPreparacion'] ?? $pedido->tiempoPreparacion;
                    if($parametros['estado']=="en proceso" )
                    {
                        $pedido->fecha = date('Y-m-d H:i:s');
                    }
                    if ($parametros['estado'] == "listo para servir") {
                        if (isset($pedido->fecha, $pedido->tiempoPreparacion)) {
                            $fechaConvertida = new DateTime($pedido->fecha);
                            $fechaConvertida->add(new DateInterval('PT' . $pedido->tiempoPreparacion . 'M'));
                            $tiempoActual = new DateTime();
                            $tiempoRestante = $fechaConvertida->getTimestamp() - $tiempoActual->getTimestamp();
                            $pedido->tiempoEntrega = $tiempoRestante / 60;
                        } else {
                            $pedido->tiempoEntrega = null;
                        }
                    } 
                }
                if(isset ($parametros['estado']))
                {
                    if($parametros['estado']=="pendiente" || $parametros['estado']=="en proceso" || $parametros['estado']=="entregado"|| $parametros['estado']=="listo para servir" ||isset($parametros['estado'])==false )
                    {
                        $pedido->estado = $parametros['estado']?? $pedido->estado;     
                    } else
                    {
                        $payload = json_encode(array("mensaje" => "Estado inexistente, debe ser: pendiente, en proceso, listo para servir o entregado"));
                        $bandera=1;
                    }
                }
                    
                $pedido->sector = $producto->sector ?? $pedido->sector;
                if($bandera==0)
                {
                    if($pedido->modificarPedido())
                    {
                        $payload = json_encode(array("mensaje" => "Pedido modificado con éxito"));
                    } else
                    {
                        $payload = json_encode(array("mensaje" => "Hubo un error al modificar la base de datos"));
                    }
                }
            } else
            {
                $payload = json_encode(array("mensaje" => "Acceso restringido a este pedido"));
            }      
        } else {
            $payload = json_encode(array("mensaje" => "ID de pedido no encontrado"));
        }
      } else
      {
        $payload = json_encode(array("mensaje" => "ID a modificiar no ingresado"));
      } 
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  
  

  public function BorrarUno($request, $response, $args)
  {
    $usuario=LogInController::ObtenerData($request);
    $logController = new LogController();
    $logController->agregarLog($usuario->usuario,"Borrar un pedido");
      $parametros = $request->getParsedBody();
      if (isset($parametros['id']))
      {
        $id = $parametros['id'];
        // $id = $args['id'];
      // Llama al método para borrar el pedido por su ID
        if(Pedido::borrarPedido($id))
        {
            $payload = json_encode(array("mensaje" => "Pedido borrado con éxito"));
        } else
        {
            $payload = json_encode(array("mensaje" => "Error al intentar borrar el pedido"));
        }
      } else
      {
        $payload = json_encode(array("mensaje" => "Producto no ingresado"));
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  
}