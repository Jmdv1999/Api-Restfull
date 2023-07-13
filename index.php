<?php
    require_once "clases/conexion/conexion.php";

    $conexion = new Conexion;

    //$query = "INSERT INTO productos (nombre, id_categoria, precio_compra, precio_venta, codigo, cantidad, descripccion) VALUES ('Arroz','1', '17', '22', '7777999', 89, 'Mary clasico')";
    //$query = "SELECT p.*, c.nombre AS nombre_categoria FROM productos AS p INNER JOIN categorias AS c ON p.id_categoria = c.id";

    //print_r($conexion->noQueryID($query));
    echo "Hello world";
?>