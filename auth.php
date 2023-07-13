<?php
    require_once "clases/auth.class.php";
    include_once "clases/respuestas.class.php";

    $_auth = new Auth;
    $_respuestas = new Respuetas;

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        //Recibimos los datos
        $postBody = file_get_contents("php://input");
        //Enviamos al manejador
        $datosArray = $_auth->login($postBody);
        //Devolvemos una respuesta
        header('content-type: application/json');
        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        }
        else{
            http_response_code(200);
        }
        echo json_encode($datosArray);
    }
    else{
        header('content-type: application/json');
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
    }
?>