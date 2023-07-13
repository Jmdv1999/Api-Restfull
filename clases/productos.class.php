<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";
class Productos extends Conexion
{
    private $id;
    private $nombre;
    private $id_categoria;
    private $precio_compra;
    private $precio_venta;
    private $cantidad;
    private $descripcion;
    private $token;
    //1374a08cbce025feeaa4333b27e8c150

    public function listarProductos($pagina = 1)
    {
        $inicio = 0;
        $cantidad = 100;
        if ($pagina > 1) {
            $inicio = ($cantidad * ($pagina - 1)) + 1;
            $cantidad = $cantidad * $pagina;
        }

        $query = "SELECT p.id, p.nombre, p.precio_venta, p.cantidad, c.nombre AS categoria, c.id AS id_categoria FROM productos AS p INNER JOIN categorias as c ON p.id_categoria=c.id LIMIT $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        return $datos;
    }
    public function obtenerProducto($id)
    {
        $query = "SELECT p.*, c.nombre AS categoria FROM productos AS p INNER JOIN categorias as c ON p.id_categoria=c.id WHERE p.id = $id";
        return parent::obtenerDatos($query);
    }
    public function detectarPost($json)
    {
        $_respuestas = new Respuetas;
        $datos = json_decode($json, true);
        if (!isset($datos['token'])) {
            return $_respuestas->error_401();
        } else {
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if ($arrayToken) {
                if (!isset($datos['nombre']) || !isset($datos['id_categoria'])) {
                    return $_respuestas->error_400();
                } else {
                    $this->nombre = $datos['nombre'];
                    $this->id_categoria = $datos['id_categoria'];
                    if (isset($datos['precio_compra'])) {
                        $this->precio_compra = $datos['precio_compra'];
                    }
                    if (isset($datos['precio_venta'])) {
                        $this->precio_venta = $datos['precio_venta'];
                    }
                    if (isset($datos['cantidad'])) {
                        $this->cantidad = $datos['cantidad'];
                    }
                    if (isset($datos['descripcion'])) {
                        $this->descripcion = $datos['descripcion'];
                    }
                    $resp = $this->insertarPoducto();
                    if ($resp) {
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "producto_id" => $resp,
                        );
                        return $respuesta;
                    } else {
                        return $_respuestas->error_500();
                    }
                }
            } else {
                return $_respuestas->error_401("El token que se envio no es valido o esta caducado");
            }
        }
    }
    public function detectarPut($json)
    {
        $datos = json_decode($json, true);
        $_respuestas = new Respuetas;
        if (!isset($datos['token'])) {
            return $_respuestas->error_401();
        }else {
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if ($arrayToken) {
                if (!isset($datos['id'])) {
                    return $_respuestas->error_400();
                } else {
                    $this->id = $datos['id'];
                    if (isset($datos['nombre'])) {
                        $this->nombre = $datos['nombre'];
                    }
                    if (isset($datos['id_categoria'])) {
                        $this->id_categoria = $datos['id_categoria'];
                    }
                    if (isset($datos['precio_compra'])) {
                        $this->precio_compra = $datos['precio_compra'];
                    }
                    if (isset($datos['precio_venta'])) {
                        $this->precio_venta = $datos['precio_venta'];
                    }
                    if (isset($datos['cantidad'])) {
                        $this->cantidad = $datos['cantidad'];
                    }
                    if (isset($datos['descripcion'])) {
                        $this->descripcion = $datos['descripcion'];
                    }
                    $resp = $this->modificarProducto();

                    if ($resp) {
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "producto_id" => $this->id,
                        );
                        return $respuesta;
                    } else {
                        return $_respuestas->error_500();
                    }
                }
            } else {
                return $_respuestas->error_401("El token que se envio no es valido o esta caducado");
            }
        }
    }

    public function detectarDelete($json)
    {
        $datos = json_decode($json, true);
        $_respuestas = new Respuetas;
        if (!isset($datos['token'])) {
            return $_respuestas->error_401();
        } else {
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if ($arrayToken) {
                if (!isset($datos['id'])) {
                    return $_respuestas->error_400();
                } else {
                    $this->id = $datos['id'];
                    $resp = $this->EliminarProducto();
                    if ($resp) {
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "producto_id" => $this->id,
                        );
                        return $respuesta;
                    } else {
                        return $_respuestas->error_500();
                    }
                }
            } else {
                return $_respuestas->error_401("El token que se envio no es valido o esta caducado");
            }
        }
    }

    public function insertarPoducto()
    {
        $sql = "INSERT INTO productos (nombre, id_categoria, precio_compra, precio_venta, cantidad, descripcion) 
            VALUES ('" . $this->nombre . "', '" . $this->id_categoria . "', '" . $this->precio_compra . "', '" . $this->precio_venta . "', '" . $this->cantidad . "', '" . $this->descripcion . "')";
        $resp = parent::noQueryId($sql);
        if ($resp) {
            return $resp;
        } else {
            return 0;
        }
    }
    public function modificarProducto()
    {
        $sql = "UPDATE productos SET nombre = '" . $this->nombre . "', id_categoria ='" . $this->id_categoria . "', precio_compra = '" . $this->precio_compra . "', precio_venta = '" . $this->precio_venta . "', cantidad = '" . $this->cantidad . "', descripcion = '" . $this->descripcion . "' WHERE id = '" . $this->id . "'";
        $resp = parent::noQuery($sql);
        print_r($resp);
        if ($resp >= 1) {
            return $resp;
        } else {
            return 0;
        }
    }
    public function EliminarProducto()
    {
        $sql = "DELETE FROM productos WHERE id = '" . $this->id . "'";
        $resp = parent::noQuery($sql);
        if ($resp >= 1) {
            return $resp;
        } else {
            return 0;
        }
    }
    private function buscarToken()
    {
        $sql = "SELECT id, usuario_id, estado FROM usuarios_token WHERE token = '" . $this->token . "' AND estado = 'Activo'";
        $resp = parent::obtenerDatos($sql);
        if ($resp) {
            return $resp;
        } else {
            return 0;
        }
    }
    private function actualizarToken($tokenid)
    {
        $fecha = date("Y m d H:i");
        $query = "UPDATE usuarios_token SET fecha = '$fecha' WHERE id = '$tokenid'";
        $resp = parent::noQueryId($query);
        if ($resp >= 1) {
            return $resp;
        } else {
            return 0;
        }
    }
}
