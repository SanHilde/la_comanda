<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $usuario=LogInController::ObtenerData($request);
    $logController = new LogController();
    $logController->agregarLog($usuario->usuario,"Cargar un producto");
      $parametros = $request->getParsedBody();
      
      if(isset($parametros['descripcion']) && isset($parametros['precio']) && isset($parametros['sector']) )
      {
        if($parametros['sector'] == "cocina" || $parametros['sector'] == "candybar" || $parametros['sector'] == "bartender" || $parametros['sector'] == "cervecero")
        {
            $producto = new Producto();
            $producto->descripcion = $parametros['descripcion'];
            $producto->precio = $parametros['precio'];
            $producto->sector = $parametros['sector'];
            $producto->crearUno();
            $payload = json_encode(array("mensaje" => "Producto creado con éxito"));
        } else
        {
            $payload = json_encode(array("mensaje" => "Sector inexistente"));

        }
      } else
      {
        $payload = json_encode(array("mensaje" => "Falta ingresar paramtros"));
      }

  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  

  public function TraerUno($request, $response, $args)
  {
    $usuario=LogInController::ObtenerData($request);
    $logController = new LogController();
    $logController->agregarLog($usuario->usuario,"Traer un producto");
      // Buscamos Producto por nombre
      $nombre = $args['producto']; // Suponiendo que el nombre del producto se obtiene de los parámetros de la ruta
    // $parametros = $request->getParsedBody();
    // $nombre = $parametros['producto'];
      $producto = Producto::obtenerProducto($nombre);
      if ($producto) {
          $payload = json_encode($producto);
      } else {
          $payload = json_encode(array("mensaje" => "Producto no encontrado"));
      }
      
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  public static function Validar($producto)
  {
      if(Producto::obtenerProducto($producto)!= null)
      {
          return true;
      } else
      {
          return false;
      }

  }

  public function TraerTodos($request, $response, $args)
  {
    $usuario=LogInController::ObtenerData($request);
    $logController = new LogController();
    $logController->agregarLog($usuario->usuario,"Traer todos los productos");
      $lista = Producto::obtenerTodos();
      $payload = json_encode($lista);
  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
    
  public function ModificarUno($request, $response, $args)
  {
    $usuario=LogInController::ObtenerData($request);
    $logController = new LogController();
    $logController->agregarLog($usuario->usuario,"Modificar un producto");
      $parametros = $request->getParsedBody();
  
     // Suponiendo que el nombre del producto se pasa en el cuerpo de la solicitud
    if(isset($parametros['descripcion']))
    {
      // Obtén el producto que deseas modificar por su nombre
      $producto = Producto::obtenerProducto($parametros['descripcion']);
  
      if ($producto) {
          // Actualiza los atributos del producto con los nuevos valores
          $producto->descripcion = $parametros['descripcionNueva']??$producto->descripcion;
          $producto->precio = $parametros['precio']??$producto->precio;
          $producto->sector = $parametros['sector']??$producto->sector;
  
          // Llama al método para modificar el producto en la base de datos
          $producto->modificarProducto();
  
          $payload = json_encode(array("mensaje" => "Producto modificado con éxito"));
      } else {
          $payload = json_encode(array("mensaje" => "Producto no encontrado"));
      }
    }else
    {
        $payload = json_encode(array("mensaje" => "Es necesario ingresar el producto a modificar"));
    }

  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  

  public function BorrarUno($request, $response, $args)
  {
    $usuario=LogInController::ObtenerData($request);
    $logController = new LogController();
    $logController->agregarLog($usuario->usuario,"Borrar un producto");
      $parametros = $request->getParsedBody();
        if (isset($parametros['producto']))
        {
            $producto = Producto::obtenerProducto($parametros['producto']);
            if(Producto::borrarProducto($producto->id))
            {
                $payload = json_encode(array("mensaje" => "Producto borrado con éxito"));
            } else
            {
                $payload = json_encode(array("mensaje" => "Error al querer eliminar el producto"));
            }
           
        }else
        {
            $payload = json_encode(array("mensaje" => "Debe ingresar el producto a eliminar"));
        }
  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  
}