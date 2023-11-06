<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
      $parametros = $request->getParsedBody();
      
      $producto = new Producto();
      $producto->descripcion = $parametros['descripcion'];
      $producto->precio = $parametros['precio'];
      $producto->tipo = $parametros['tipo'];
  
      $producto->crearProducto();
  
      $payload = json_encode(array("mensaje" => "Producto creado con éxito"));
  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  

  public function TraerUno($request, $response, $args)
  {
      // Buscamos Producto por nombre
      $nombre = $args['nombre']; // Suponiendo que el nombre del producto se obtiene de los parámetros de la ruta
      $producto = Producto::obtenerProducto($nombre);
      
      if ($producto) {
          $payload = json_encode($producto);
      } else {
          $payload = json_encode(array("mensaje" => "Producto no encontrado"));
      }
  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  

  public function TraerTodos($request, $response, $args)
  {
      $lista = Producto::obtenerTodos();
      $payload = json_encode($lista);
  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  
    
  public function ModificarUno($request, $response, $args)
  {
      $parametros = $request->getParsedBody();
  
      $nombre = $parametros['nombre']; // Suponiendo que el nombre del producto se pasa en el cuerpo de la solicitud
      $descripcion = $parametros['descripcion'];
      $precio = $parametros['precio'];
      $tipo = $parametros['tipo'];
  
      // Obtén el producto que deseas modificar por su nombre
      $producto = Producto::obtenerProducto($nombre);
  
      if ($producto) {
          // Actualiza los atributos del producto con los nuevos valores
          $producto->descripcion = $descripcion;
          $producto->precio = $precio;
          $producto->tipo = $tipo;
  
          // Llama al método para modificar el producto en la base de datos
          $producto->modificarProducto();
  
          $payload = json_encode(array("mensaje" => "Producto modificado con éxito"));
      } else {
          $payload = json_encode(array("mensaje" => "Producto no encontrado"));
      }
  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  

  public function BorrarUno($request, $response, $args)
  {
      $parametros = $request->getParsedBody();
  
      $ProductoId = $parametros['ProductoId']; // Suponiendo que el ID del producto se pasa en el cuerpo de la solicitud
  
      // Llama al método para borrar el producto por su ID
      Producto::borrarProducto($ProductoId);
  
      $payload = json_encode(array("mensaje" => "Producto borrado con éxito"));
  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  
}