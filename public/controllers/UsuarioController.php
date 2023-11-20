<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
      $parametros = $request->getParsedBody();
    if ( isset($parametros['nombre']) && isset($parametros['clave']) && isset($parametros['sector']) )
    {
        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];
        $sector = $parametros['sector'];
        if($sector == "cocina" || $sector == "socio" || $sector == "bartender" || $sector == "candybar" || $sector == "cervecero" || $sector == "mozo")
        {
          $usr = new Usuario();
          $usr->nombre = $nombre;
          $usr->clave = $clave;
        //   $usr->tipo = $tipo;
          $usr->sector = $sector;
          $usr->crearUno();
      
          $payload = json_encode(array("mensaje" => "Usuario creado con éxito"));
        } else
        {
          $payload = json_encode(array("mensaje" => "Sector inexistente, debe ser: socio, cocina, bartender, candybar,cervecero o mozo"));
        }
       
    } else
    {
      $payload = json_encode(array("mensaje" => "Falta ingresar un parametro"));
    }
      
  
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
        return $response->withHeader('Content-Type', 'application/json');
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
        
        if(isset($parametros['usuarioNuevo']) || isset($parametros['clave']) || isset($parametros['sector']) || isset($parametros['usuario']) )
        {
          $nombre = $parametros['usuarioNuevo'];
          $clave = $parametros['clave'];
          // $tipo = $parametros['tipo'];
          $sector = $parametros['sector'];
          
          $usuario = Usuario::obtenerUsuario($parametros['usuario']);
          
          if ($usuario) {
  
              $usuario->nombre = $nombre ?? $usuario->nombre;
              $usuario->clave = $clave ?? $usuario->clave ;
              // $usuario->tipo = $tipo;
              $usuario->sector = $sector ??  $usuario->sector;
              if($sector == "cocina" || $sector == "socio" || $sector == "bartender" || $sector == "candybar" || $sector == "cervecero" || $sector == "mozo")
              {
              $usuario->modificarUsuario();
          
              $payload = json_encode(array("mensaje" => "Usuario modificado con éxito"));
              }else
              {
                $payload = json_encode(array("mensaje" => "Sector inexistente, debe ser: socio, cocina, bartender, candybar,cervecero o mozo"));
              }
            } else {
              $payload = json_encode(array("mensaje" => "Usuario no encontrado"));
          }
        }else
        {
          $payload = json_encode(array("mensaje" => "Falta ingresar parametros"));
        }


        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }    

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
      if(isset($parametros['usuario']))
      {
        $usuario = Usuario::obtenerUsuario($parametros['usuario']);
          if ($usuario) {
            if(Usuario::borrarUsuario($usuario->id))
            {
              $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
            }else
            {
              $payload = json_encode(array("mensaje" => "Error al borrar el usuario"));
            }
          } else {
              $payload = json_encode(array("mensaje" => "Usuario no encontrado"));
          }
      }else {
        $payload = json_encode(array("mensaje" => "Falta ingresar ID"));
      }
      
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}