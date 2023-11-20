<?php
require_once './models/Archivos.php';
require_once './models/Producto.php';
require_once './models/Usuario.php';
require_once './models/Mesa.php';
require_once './models/Pedido.php';

class ArchivoController //extends Archivos
{
    public function ExportarArchivo($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        
        // $nombreArchivo = $args['nombreArchivo'];
        if (isset($parametros['nombreArchivo']) && isset($parametros['datosDeseados']))
        {
            $nombreArchivo=$parametros['nombreArchivo'];
            $datosDeseados=$parametros['datosDeseados'];
            
            $archivo = new Archivos ("./Archivos exportados/",$nombreArchivo);
            $lista=array();
            switch($datosDeseados)
            {
                case "productos":
                    $lista = Producto::obtenerTodos();
                    break;
                case "mesas":
                    $lista = Mesa::obtenerTodos();
                    break;
                case "pedidos":
                    $lista = Pedido::obtenerTodos(null,null);
                    break;
                case "usuarios":
                    $lista = Usuario::obtenerTodos();
                    break;
                default:
                    $lista=null;
                break;
            }
    
            if($lista!=null)
            {
                
                $archivo->ActualizarArchivo(Archivos::ConvertirJsonACSV($lista));
                $payload = json_encode(array("mensaje" => "Archivo exportado con éxito"));
            } else
            {
                $payload = json_encode(array("mensaje" => "Los datos solicitados no existen, puede ser productos, mesas, pedidos o usuarios"));
            }
            
        }else
        {
            $payload = json_encode(array("mensaje" => "Faltan ingresar parametros"));
        }


        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ImportarArchivo($request, $response, $args)
    {
        $parametros=$request->getUploadedFiles();
        $bandera=false;
        if (isset($parametros['archivo']))
        {
            $nombreArchivo = $parametros['archivo']->getClientFilename();
            $archivoSeparado=explode(".",$nombreArchivo);
            switch($archivoSeparado[1])
            {
                case "csv":
                    $string = $parametros['archivo']->getStream();
                    $json = Archivos::ConvertirStringCSVAJson($string);
                    $payload = json_encode(array("mensaje" => "Archivo importado con exito"));
                    $bandera = true;
                    break;
                default:
                    $payload = json_encode(array("mensaje" => "Extension del archivo no valida"));
                    break;
            }
            if($bandera==true)
            {
                $nombre = $archivoSeparado[0];
                switch ($nombre)
                {
                    case "productos":
                        $objetoCastado = new Producto();
                        self::CastearObjeto($json,$objetoCastado);  
                        break;
                    case "mesas":
                        $objetoCastado = new Mesa();
                        self::CastearObjeto($json,$objetoCastado);  
                        break;
                    case "usuarios":
                        $objetoCastado = new Usuario();
                        self::CastearObjeto($json,$objetoCastado);  
                        break;
                    case "pedidos":
                        $objetoCastado = new Pedido();
                        self::CastearObjeto($json,$objetoCastado);  
                        break;
                    default:
                    $payload = json_encode(array("mensaje" => "$nombre.csv no es valido, el archivo puede ser: productos.csv, mesas.csv, usuarios.csv y pedidos.csv"));
                    break;
                }
            }


        } else
        {
            $payload = json_encode(array("mensaje" => "Faltan ingresar parametros"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function CastearObjeto($json,&$objetoCastado)
    {
        foreach($json as $objeto)
        {
            foreach ($objeto as $nombrePropiedad => $valorPropiedad) {
                if($nombrePropiedad=="mozo")
                {
                    $usuario=Usuario::obtenerUsuario($valorPropiedad);
                    $objetoCastado->{$nombrePropiedad}  = $usuario->id;
                }else
                {
                    if($nombrePropiedad=="producto")
                    {
                        $producto=Producto::obtenerProducto($valorPropiedad);
                        $objetoCastado->{$nombrePropiedad}  = $producto->id;
                    } else
                    {
                        $objetoCastado->{$nombrePropiedad}  = $valorPropiedad;
                    }             
                }               
            }
            $objetoCastado->crearUno();
        }
    }
}   