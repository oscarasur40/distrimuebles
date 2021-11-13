<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Consultas
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }


    //Implementar un método para listar los registros
    public function Comprasfecha($fecha_inicio,$fecha_fin)
    {
        $sql="SELECT DATE(i.fecha_hora) as fecha,u.nombre as
        usuario, p.nombre as proveedor,i.tipo_comprobante,i.
        serie_comprobante,i.num_comprobante,i.total_compra,i.
        impuesto,i.estado FROM  ingreso i INNER JOIN persona
        p ON i.idproveedor=p.idpersona INNER JOIN usuario u
        ON i.idusuario=u.idusuario WHERE DATE(i.fecha_hora)>=
        '$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin'"
        ;
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros
    public function vetasfechacliente($fecha_inicio,$fecha_fin,$idcliente)
    {
        $sql="SELECT DATE(v.fecha_hora) as fecha,u.nombre as
        usuario, p.nombre as cliente,v.tipo_comprobante,v.
        serie_comprobante,v.num_comprobante,v.total_venta,v.
        impuesto,v.estado FROM  venta v INNER JOIN persona
        p ON v.idcliente=p.idpersona INNER JOIN usuario u
        ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>=
        '$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idcliente='$idcliente'"
        ;
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros
    public function cierre($fecha_inicio,$fecha_fin,$idusuario)
    {
        $sql="SELECT DATE(v.fecha_hora) as fecha,u.nombre as
        usuario,v.tipo_comprobante,v.
        serie_comprobante,v.num_comprobante,v.total_venta,v.
        impuesto,v.estado FROM  venta v INNER JOIN usuario
        u ON v.idusuario=u.idusuario  WHERE DATE(v.fecha_hora)>=
        '$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idusuario='$idusuario'"
        ;
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros
    public function totalcomprashoy()
    {
       $sql="SELECT IFNULL(SUM(total_compra),0) as total_compra
       FROM ingreso WHERE fecha_hora=CURRENT_TIME";
       return ejecutarConsulta($sql);

     }

    //Implementar un método para listar los registros
    public function totalventashoy()
    {
        $sql="SELECT IFNULL(SUM(total_venta),0) as total_venta
       FROM venta WHERE fecha_hora=CURRENT_TIME";
        return ejecutarConsulta($sql);

    }

    public function comprasultimos10dias()
    {
        $sql="SELECT CONCAT(DAY(fecha_hora),'-',MONTH(fecha_hora)) as fecha,
         SUM(total_compra) as total FROM ingreso GROUP BY fecha_hora ORDER BY fecha_hora DESC limit 0,10";
        return ejecutarConsulta($sql);
    }
}

?>