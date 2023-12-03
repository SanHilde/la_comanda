<?php

class Encuesta
{
    public $id;
    public $pedido;
    public $mesa;
    public $restaurante;
    public $mozo;
    public $cocinero;
    public $comentarios;

    public function crearUno()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuesta (pedido, mesa, restaurante, mozo, cocinero, comentarios) 
            VALUES (:pedido, :mesa, :restaurante, :mozo, :cocinero, :comentarios)");

        $consulta->bindValue(':pedido', $this->pedido, PDO::PARAM_STR);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT);
        $consulta->bindValue(':restaurante', $this->restaurante, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':cocinero', $this->cocinero, PDO::PARAM_INT);
        $consulta->bindValue(':comentarios', $this->comentarios, PDO::PARAM_STR);

        $consulta->execute();
         
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, pedido, mesa, restaurante, mozo, cocinero, comentarios FROM encuesta");
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }
    
    public static function obtenerEncuesta($pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, pedido, mesa, restaurante, mozo, cocinero, comentarios FROM encuesta WHERE pedido = :pedido");
        $consulta->bindValue(':pedido', $pedido, PDO::PARAM_STR);
        $consulta->execute();
    
        return $consulta->fetchObject('Encuesta');
    }
    

    public function modificarEncuesta()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE encuesta SET mesa = :mesa, restaurante = :restaurante, mozo = :mozo, cocinero = :cocinero, comentarios = :comentarios WHERE pedido = :pedido");
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT);
        $consulta->bindValue(':restaurante', $this->restaurante, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':cocinero', $this->cocinero, PDO::PARAM_INT);
        $consulta->bindValue(':comentarios', $this->comentarios, PDO::PARAM_STR);
        $consulta->bindValue(':pedido', $this->pedido, PDO::PARAM_STR);
        
        return $consulta->execute();
    }

    public function borrarEncuesta($pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM encuesta WHERE pedido = :pedido");
        $consulta->bindValue(':pedido', $pedido, PDO::PARAM_STR);
        return $consulta->execute();
    }


    


}