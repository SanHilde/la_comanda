<?php

class Usuario
{
    public $id;
    public $nombre;
    public $clave;
    // public $tipo;
    public $sector;

    // public function __construct($id, $nombre, $clave, $tipo, $sector)
    // {
    //     $this->id = $id;
    //     $this->nombre = $nombre;
    //     $this->clave = $clave;
    //     $this->tipo = $tipo;
    //     $this->sector = $sector;
    // }

    public function crearUno()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (nombre, clave, sector) VALUES (:nombre, :clave, :sector)");
        // $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        // $consulta->bindValue(':id', $id, PDO::PARAM_INT); // id como ID
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        // $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        return $consulta->execute();
    }
    
    

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, clave, sector FROM usuarios");
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }
    

    public static function obtenerUsuario($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, clave, sector FROM usuarios WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();
    
        return $consulta->fetchObject('Usuario');
    }
    
    

    public function modificarUsuario()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET nombre = :nombre, clave = :clave, sector = :sector WHERE id = :id");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        // $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }
    

    public static function borrarUsuario($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET sector = 'dado de baja' WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        return ($consulta->execute());
    }   
    
}