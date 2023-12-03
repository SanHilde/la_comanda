<?php
require_once './models/Archivos.php';
require_once './models/Producto.php';
require_once './models/Usuario.php';
require_once './models/Mesa.php';
require_once './models/Pedido.php';

//use setasign\Fpdf\Fpdf;


class ArchivoController //extends Archivos
{
    public function ExportarArchivo($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario=LogInController::ObtenerData($request);
        $logController = new LogController();
        $logController->agregarLog($usuario->usuario,"Exportar archivo");
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
        $usuario=LogInController::ObtenerData($request);
        $logController = new LogController();
        $logController->agregarLog($usuario->usuario,"Importar archivo");
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

    public function DescargaPDF($request, $response, $args)
    {
        $usuario=LogInController::ObtenerData($request);
        $logController = new LogController();
        $logController->agregarLog($usuario->usuario,"Descargar PDF");
        $parametros = $request->getParsedBody();
        if (isset($parametros['datosDeseados']))
        {
            $usuario=LogInController::ObtenerData($request);
            $datosDeseados=$parametros['datosDeseados'];
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
                $pdf = new FPDF();
                $pdf->AddPage();
                $logoPath = '.././logo-compania.png';
                $logoX = 160; // posición X del logo
                $logoY = 10;  // posición Y del logo
                $logoWidth = 30; // ancho del logo
                $logoHeight = 30; // alto del logo

                // Insertar el logo
                
                $fecha = new datetime("now");
                $pdf->Image($logoPath, $logoX, $logoY, $logoWidth, $logoHeight);
                
                $pdf->SetFont('Helvetica', 'B', 20);
                $pdf->Cell(160, 15, 'Datos exportados', 0, 3, 'L');
                $pdf->Ln(3);

                $pdf->SetFont('Helvetica', '', 15);
                $pdf->Cell(60, 4, "Nombre: $usuario->usuario", 0, 1, 'L');
                $pdf->Cell(20, 0, '', 'T');
                $pdf->Ln(3);
                
                $pdf->Cell(60, 4, "Listado de $datosDeseados:", 0, 1, 'L');
                // $pdf->Cell(60, 0, '', 'T');
                $pdf->Ln(5);

                $header = self::obtenerKeysPrimerObjeto($lista);
                
                $pdf->SetFillColor(255, 0, 0);
                $pdf->SetTextColor(255);
                $pdf->SetDrawColor(128, 0, 0);
                $pdf->SetLineWidth(.3);
                $pdf->SetFont('Helvetica', 'B', 8);

                foreach ($header as $key) {
                    $maxAncho = strlen($key); // Inicializa con el ancho de la key
                    foreach ($lista as $obj) {
                        $value = $obj->$key;
                        $maxAncho = max($maxAncho, strlen($value));
                    }
                    $anchosColumna[$key] = $maxAncho;
                }
                foreach ($header as $key) {
                    $ancho = $anchosColumna[$key];
                    $pdf->Cell($ancho * 2, 7, $key, 1, 0, 'C', true); 
                }         
                $pdf->Ln();

                $pdf->SetFillColor(224, 235, 255);
                $pdf->SetTextColor(0);
                $pdf->SetFont('');

                $fill = false;

                foreach ($lista as $obj) {
                    foreach ($header as $key) {
                        $value = $obj->$key;
                        $ancho = $anchosColumna[$key];
                        $pdf->Cell($ancho * 2, 6, strval($value), 'LR', 0, 'C', $fill); 
                    }
                    $pdf->Ln();
                    $fill = !$fill;
                }

                if (!file_exists('./ArchivosPDF/')) {
                    mkdir('./ArchivosPDF/', 0777, true);
                }
                       
                $pdf->Cell( array_sum($anchosColumna) * 2, 0, '', 'T');

                $nombreArchivo = "./ArchivosPDF/Listadode$datosDeseados-" . $fecha->format("Ymd_His") . '.pdf';
                
                $pdf->Output('F', $nombreArchivo, 'I');

                $payload = json_encode(array("mensaje" => "archivo generado ./ArchivosPDF/Listadode$datosDeseados-" . $fecha->format("Y-m-d") .'.pdf'));
        } else {
            $payload = json_encode(array("error" => 'Datos no encontrados'));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
    public function obtenerKeysPrimerObjeto($lista) {
        if (count($lista) > 0) {
            $primerElemento = $lista[0];

            if (is_array($primerElemento)) {
                return array_keys($primerElemento);
            } elseif (is_object($primerElemento)) {

                $arrayAsociativo = json_decode(json_encode($primerElemento), true);
                
                if (is_array($arrayAsociativo)) {
                    return array_keys($arrayAsociativo);
                }
            }
        }

        return array();
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