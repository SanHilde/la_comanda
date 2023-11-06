<?php

class Usuario
{
    public $id;
    public $nombre;
    public $clave;
    public $tipo;
    public $sector;

    // public function __construct($id, $nombre, $clave, $tipo, $sector)
    // {
    //     $this->id = $id;
    //     $this->nombre = $nombre;
    //     $this->clave = $clave;
    //     $this->tipo = $tipo;
    //     $this->sector = $sector;
    // }

    public function crearUsuario($dni)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (id, nombre, clave, tipo, sector) VALUES (:id, :nombre, :clave, :tipo, :sector)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':id', $dni, PDO::PARAM_INT); // DNI como ID
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->execute();
    
        return $dni; 
    }
    
    

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, clave, tipo, sector FROM usuarios");
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }
    

    public static function obtenerUsuario($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, clave, tipo, sector FROM usuarios WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    
        return $consulta->fetchObject('Usuario');
    }
    
    

    public function modificarUsuario()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET nombre = :nombre, clave = :clave, tipo = :tipo, sector = :sector WHERE id = :id");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }
    

    public function borrarUsuario()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM usuarios WHERE id = :id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }
    
}