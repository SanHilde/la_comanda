<?php

class Mesa
{
    public $id; // Identificador de la mesa
    public $estado; // Estado de la mesa (por ejemplo, libre, ocupada)
    public $mozo; // ID del mozo asignado a la mesa
    public $pedido; // Pedido asociado a la mesa
    
    public function crearUno()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (estado, mozo, pedido) VALUES (:estado, :mozo, :pedido)");
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':pedido', $this->pedido, PDO::PARAM_STR);
        $consulta->execute();
        
        return $objAccesoDatos->obtenerUltimoId();
    }
    
     

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT m.id, m.estado, u.nombre as mozo, m.pedido
        FROM mesas m
        INNER JOIN usuarios u ON m.mozo = u.id");
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }
    
    
    

    public static function obtenerMesa($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, mozo, pedido FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    
        return $consulta->fetchObject('Mesa');
    }
    
    
    public function modificarMesa()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estado = :estado, mozo = :mozo, pedido = :pedido WHERE id = :id");
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':pedido', $this->pedido, PDO::PARAM_INT); // Ajusta el tipo de dato según el tipo real de la columna
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $consulta->execute();
    }
    

    public function borrarMesa($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estado = 'dado de baja', mozo = '-1',, pedido = '-' WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        return $consulta->execute();
    }
    
    
}