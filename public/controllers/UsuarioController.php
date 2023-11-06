<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
      $parametros = $request->getParsedBody();
  
      $dni = $parametros['dni'];
      $nombre = $parametros['nombre'];
      $clave = $parametros['clave'];
      $tipo = $parametros['tipo'];
      $sector = $parametros['sector'];
  
      $usr = new Usuario();
      $usr->nombre = $nombre;
      $usr->clave = $clave;
      $usr->tipo = $tipo;
      $usr->sector = $sector;
      $usr->crearUsuario($dni);
  
      $payload = json_encode(array("mensaje" => "Usuario creado con éxito"));
  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        
        $id = $parametros['dni']; 
        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];
        $tipo = $parametros['tipo'];
        $sector = $parametros['sector'];
        
        $usuario = Usuario::obtenerUsuarioPorId($id);
        
        if ($usuario) {

            $usuario->nombre = $nombre;
            $usuario->clave = $clave;
            $usuario->tipo = $tipo;
            $usuario->sector = $sector;
        
            $usuario->modificarUsuario();
        
            $payload = json_encode(array("mensaje" => "Usuario modificado con éxito"));
        } else {
            $payload = json_encode(array("mensaje" => "Usuario no encontrado"));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }    
    

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['dni'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}