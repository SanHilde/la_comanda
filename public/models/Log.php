<?php
require_once './db/AccesoDatos.php';

class Log
{
    public $id;
    public $user;
    public $fecha;
    public $accion;

    public function crearUno()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO log (user, fecha, accion) VALUES (:user, :fecha, :accion)");

        $consulta->bindValue(':user', $this->user, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR); // Suponiendo que $fecha es un string en formato adecuado.
        $consulta->bindValue(':accion', $this->accion, PDO::PARAM_STR);

        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }
    
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        
        $consultaString = "SELECT l.id, l.user, l.fecha, l.accion FROM log l";
        
        $consulta = $objAccesoDatos->prepararConsulta($consultaString);
        $consulta->execute();
        
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Log');
    }


}