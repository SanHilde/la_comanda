<?php
class Archivos 
{
    private $archivo;

    public function __construct($ubicacionDelArchivo,$nombreArchivo) 
    {
        // $this->archivo = $nombreArchivo;
        $ruta="$ubicacionDelArchivo$nombreArchivo";
        $this->archivo = $ruta;

        if (file_exists($ubicacionDelArchivo) ==false)
        {
            mkdir ($ubicacionDelArchivo,0777,true);
        }
       
        if (!file_exists($this->archivo))
        {
            $archivoCreado = fopen($ruta,"w");
            fclose ($archivoCreado);
        }

    }
    public function LeerArchivo()
    {
        $array = array();
            $archivo = fopen($this->archivo,"r");
                while(!feof($archivo))
                {
                    array_push ($array,fgets($archivo));           
                }
                fclose ($archivo);
        return $array;
    }
    
    public static function ConvertirJsonACSV($json)
{
    $array = array();
    $bandera = true;
    $encabezado="";

    foreach ($json as $objeto) {
        $string = "";

        foreach ($objeto as $parametro => $valor) {
            $string .= $valor . ",";
            if ($bandera) {
                $encabezado .= $parametro . ",";
            }
        }

        if ($bandera) {
            $encabezado = rtrim($encabezado, ',');
            array_push($array, $encabezado);
            $bandera = false;
        }

        $string = rtrim($string, ',');
        array_push($array, $string);
    }

    return $array;
}
public static function ConvertirStringCSVAJson($csvString)
{
    $lineas = explode("\n", trim($csvString));
    $encabezado = array_map(function ($item) {
        return trim($item, '"');
    }, explode(',', $lineas[0]));    
    $arrayDeObjetos = [];
    for ($i = 1; $i < count($lineas); $i++) {
        if (trim($lineas[$i]) !== '') {
            $valores = array_map(function ($item) {
                return trim($item, '"');
            }, explode(',', $lineas[$i]));            
            $objeto = array_combine($encabezado, $valores);
            $arrayDeObjetos[] = $objeto;
        }
    }
    return $arrayDeObjetos;
}
public static function ConvertirCSVAJson($array)
{
    $bandera = 0;
    $encabezado = array();
    $arrayDeObjetos = array();

    foreach ($array as $linea) {
        if ($bandera == 0) {
            $encabezado = explode(",", $linea);
            $bandera = 1;
        } else {
            $objetoEnArray = explode(",", $linea);
            if (count($objetoEnArray) > 0) {
                $objetoJson = new stdClass();
                for ($i = 0; $i <= count($encabezado) - 1; $i++) {
                    $objetoJson->{$encabezado[$i]} = $objetoEnArray[$i];
                }
                array_push($arrayDeObjetos, $objetoJson);
            }
        }
    }
    return $arrayDeObjetos;
}
     

    public function ActualizarArchivo($actualizacion)
    {
        $respuesta=null;
        $archivoAbierto = fopen($this->archivo,"w");
        if ($archivoAbierto== true)
        {
            foreach($actualizacion as $objeto)
            {
                if(fwrite($archivoAbierto,(json_encode($objeto)."\n"))== false)
                {
                    $respuesta = false;
                    break;
                }
            }       
            if(fclose ($archivoAbierto) && $respuesta==null);
            {
                $respuesta = true;
            }
        }
        return $respuesta;
       
    }
    public function TraerListaDeObjetosJSON()
    {
        $array = $this->LeerArchivo();
        $arrayDeObjetos = array();
        if (count($array)>0)
        {
            foreach ($array as $linea)
            {
                $obj = json_decode($linea, true);
                if ($obj != null)
                {
                    array_push($arrayDeObjetos,$obj);
                }             
            }
        }  
        return $arrayDeObjetos;
    }
    public static function SubirImagen ($archivo, $idImagen,$ubicacionArchivo)
    {
        $tipo= $archivo['type'];
        if ($tipo == "image/jpeg")
        {
            $extension = ".jpeg";
        }
        $nombre = $idImagen.$extension;
        if (file_exists($ubicacionArchivo) ==false)
        {
            mkdir ($ubicacionArchivo,0777,true);
        }
        if (move_uploaded_file ($archivo['tmp_name'],$ubicacionArchivo."/".$nombre)==true)
        {
            echo "\nArchivo ".$archivo['name'].", ahora llamado $nombre se ha subido correctamente\n";
        }else
        {
            echo "\nError al subir el archivo $nombreImagen.\n";
        }
    }
    public static function MoverArchivo ($nombre, $nuevaUbicacion, $ubicacionArchivo)
    {

        if (file_exists($nuevaUbicacion) ==false)
        {
            mkdir ($nuevaUbicacion,0777,true);
        }
        rename($ubicacionArchivo."/".$nombre.".jpeg", $nuevaUbicacion."/".$nombre.".jpeg");
    }
}
?>