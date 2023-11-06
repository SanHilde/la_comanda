<?php

class Mesa
{
    public $id; // Identificador de la mesa
    public $estado; // Estado de la mesa (por ejemplo, libre, ocupada)
    public $mozo; // ID del mozo asignado a la mesa

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (estado, mozo) VALUES (:estado, :mozo)");
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->execute();
        
        return $objAccesoDatos->obtenerUltimoId();
    }
    
     

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, mozo FROM mesas");
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }
    
    

    public static function obtenerMesa($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, mozo FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    
        return $consulta->fetchObject('Mesa');
    }
    
    public function modificarMesa()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estado = :estado, mozo = :mozo WHERE id = :id");
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    } 

    public function borrarMesa()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }
    
    
}