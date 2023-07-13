<?php
    require_once "conexion/conexion.php";
    require_once "respuestas.class.php";

    class Auth extends Conexion{
        public function login($json){
            $_respuestas = new Respuetas;
            $datos = json_decode($json, true);
            if (!isset($datos['usuario']) || !isset($datos['password'])) {
                return $_respuestas->error_400();
            }
            else{
                $usuario = $datos['usuario'];
                $password = $datos['password'];
                $password = parent::encriptar($password);
                $datos = $this->datosUsuario($usuario);
                if($datos){
                    if ($password == $datos[0]['password']) {
                        if($datos[0]['estado'] == "Activo"){
                            $verificar = $this->insertarToken($datos[0]["id"]);
                            if ($verificar) {
                                $result = $_respuestas->response;
                                $result["result"] = array(
                                    "token" => $verificar
                                );
                                return $result;
                            }
                            else{
                                return $_respuestas->error_500("Error interno, no hemos podido guardar");
                            }
                        }
                        else{
                            return $_respuestas->error_200("El usuario se encuentra inactivo");
                        }
                    }
                    else{
                        return $_respuestas->error_200("La contraseña es incorrecta");
                    }
                }
                else{
                    return $_respuestas->error_200("El usurario $usuario no exxiste");
                }
            }
        }
        private function datosUsuario($user){
            $query = "SELECT id, password, estado FROM usuarios WHERE usuario = '$user'";
            $datos = parent::obtenerDatos($query);
            if (isset($datos[0]['id'])) {
                return $datos;
            }
            else{
                return 0;
            }
        }
        private function insertarToken($userid){
            $val = true;
            $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
            $date = date("Y-m-d H:i");
            $estado = "Activo";
            $query = "INSERT INTO usuarios_token (usuario_id, token, estado, fecha) VALUES ('$userid', '$token', '$estado', '$date')";
            $verifica = parent::noQuery($query);
            if($verifica){
                return $token;
            }
            else{
                return 0;
            }
        }
    } 
?>
