<?php

class Pedido
{
    public $id; // Identificador del pedido
    public $codigoPedido; // Código del pedido (puede ser un número o cadena)
    public $producto; // Nombre o descripción del producto
    public $cantidad; // Cantidad de productos en el pedido
    public $mesa; // ID de la mesa a la que está asociado el pedido
    public $mozo; // ID del mozo que atiende el pedido
    public $fecha; // Fecha y hora del pedido (puede ser un objeto DateTime)
    public $estado; // Estado del pedido (por ejemplo, pendiente, en proceso, entregado)
    public $sector;


    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (codigoPedido, producto, cantidad, mesa, mozo, fecha, estado, sector) VALUES (:codigoPedido, :producto, :cantidad, :mesa, :mozo, :fecha, :estado, :sector)");
        // echo($this->producto);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':producto', $this->producto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR); // Suponiendo que $fecha es un string en formato adecuado.
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        
        $consulta->execute();
        var_dump($consulta);
        return $objAccesoDatos->obtenerUltimoId();
    }
    
    

    public static function obtenerTodos($estado, $sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        
        // Consulta base
        $bandera=0;
        $consultaString = "SELECT p.id, p.codigoPedido, p.cantidad, p.mesa, u.nombre as mozo, p.fecha, p.estado, p.sector, pr.descripcion as producto
        FROM pedidos p
        INNER JOIN productos pr ON p.producto = pr.id
        INNER JOIN usuarios u ON p.mozo = u.id";
        if ($estado != null || $sector != null)
        {
            $consultaString= "$consultaString WHERE";
            if ($estado !== null) {

                $consultaString= "$consultaString p.estado = '$estado'";
                $bandera = 1;
            }
            if ($bandera == 1 && $sector !== null)
            {
                $consultaString= "$consultaString AND"; 
            }
            if ($sector !== null) {
                $consultaString= "$consultaString p.sector = '$sector'";
            }
        }
        $consulta = $objAccesoDatos->prepararConsulta($consultaString);
        $consulta->execute();
        
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    
    

    
    

    public static function obtenerPedido($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoPedido, producto, cantidad, mesa, mozo, fecha, estado,sector FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();
    
        return $consulta->fetchObject('Pedido');
    }
    
    

    public function modificarPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET producto = :producto, cantidad = :cantidad, mesa = :mesa, mozo = :mozo, fecha = :fecha, estado = :estado, sector = :sector WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':producto', $this->producto, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_INT);
        $consulta->execute();
    }
    
    

    public function borrarPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("DELETE FROM pedidos WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_INT);
        $consulta->execute();
    }
    
    
}

