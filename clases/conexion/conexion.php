<?php
    class Conexion{
        private $server;
        private $user;
        private $pass;
        private $database;
        private $port ;
        private $conexion;
        function __construct(){
            $listaDatos = $this->datosConexion();
            foreach ($listaDatos as $key => $value) {
                $this->server = $value['server'];
                $this->user = $value['user'];
                $this->pass = $value['pass'];
                $this->database = $value['database'];
                $this->port = $value['port'];
            }
            $this->conexion = new mysqli($this->server,$this->user,$this->pass,$this->database, $this->port);
            if($this->conexion->connect_errno){
                echo "Algo va mal con la conexion";
                die();
            }
        }
        private function datosConexion(){
            $direccion = dirname(__FILE__);
            $jsonData = file_get_contents($direccion."/config");
            return json_decode($jsonData, true);
        }
        private function convertirUTF8($array){
            array_walk_recursive($array, function(&$itam, $key){
                if(!mb_detect_encoding($itam, 'utf-8', true)){
                    $itam = utf8_encode($itam);
                }
            });
            return $array;
        }
        //listar
        public function obtenerDatos($query){
            $result = $this->conexion->query($query);
            $resutlArray = array();
            foreach ($result as $key) {
                $resutlArray[]=$key;
            }
            return $this->convertirUTF8($resutlArray);
        }
        //Delete y update
        public function noQuery($query){
            $result = $this->conexion->query($query);
            return $this->conexion->affected_rows;
        }
        //Insert
        public function noQueryId($query){
            $result = $this->conexion->query($query);
            $filas = $this->conexion->affected_rows;
            if ($filas >= 1) {
                return $this->conexion->insert_id;
            }
            else{
                return 0;
            }
        }
        //Encriptar

        protected function encriptar($string){
            return md5($string);
        }
    }
?>