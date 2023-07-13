<?php
    class Respuetas{
        public $response = [
            'status' => "ok", 
            'result' => array()
        ];

        public function error_405(){
            $this->response['status'] = "error";
            $this->response['result'] = array(
                "error_id" => "405",
                "Error_msg" => "Metodo no permitido"
            );
            return $this->response;
        }
        public function error_400($valor = "Datos incorrectos"){
            $this->response['status'] = "error";
            $this->response['result'] = array(
                "error_id" => "400",
                "Error_msg" => "Datos incompletos"
            );
            return $this->response;
        }

        public function error_200($valor = "ok"){
            $this->response['status'] = "error";
            $this->response['result'] = array(
                "error_id" => "ok",
                "Error_msg" => $valor
            );
            return $this->response;
        }
        public function error_500($valor = "Error interno del servidor"){
            $this->response['status'] = "error";
            $this->response['result'] = array(
                "error_id" => "500",
                "Error_msg" => $valor
            );
            return $this->response;
        }
        public function error_401($valor = "No Autorizado"){
            $this->response['status'] = "error";
            $this->response['result'] = array(
                "error_id" => "401",
                "Error_msg" => $valor
            );
            return $this->response;
        }
    }
?>