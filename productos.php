<?php
    include_once "clases/respuestas.class.php";
    include_once "clases/productos.class.php";

    $_productos = new Productos;
    $_respuestas = new Respuetas;

    if($_SERVER['REQUEST_METHOD'] == "GET"){
       if(isset($_GET['page'])){
            $pagina = $_GET['page'];
            $lista = $_productos->listarProductos($pagina);
            header("Content-Type: application/json");
            echo json_encode($lista, true);
            http_response_code(200);
       }
       else if(isset($_GET['id'])){
            $ProductoId = $_GET['id'];
            $datosProducto  = $_productos->obtenerProducto($ProductoId);
            $lista = $_productos->listarProductos($pagina);
            header("Content-Type: application/json");
            echo json_encode($datosProducto, true);
            http_response_code(200);

       }
    }
    else if($_SERVER['REQUEST_METHOD'] == "POST"){
        //Reibimos a tavez del metodo post
        $postBody = file_get_contents("php://input");
        //Enviamos al manejador
        $res = $_productos->detectarPost($postBody);
         //Devolvemos una respuesta
         header('content-type: application/json');
         if(isset($res["result"]["error_id"])){
             $responseCode = $res["result"]["error_id"];
             http_response_code($responseCode);
         }
         else{
             http_response_code(200);
         }
         echo json_encode($res);

    }
    else if($_SERVER['REQUEST_METHOD'] == "PUT"){
        //Recibimos a travez del metodo put
        $postBody = file_get_contents("php://input");
        //Enviamos datos al manejador
        $res = $_productos->detectarPut($postBody);
        //Devolvemos una respuesta
        header('content-type: application/json');
        if(isset($res["result"]["error_id"])){
             $responseCode = $res["result"]["error_id"];
             http_response_code($responseCode);
         }
         else{
             http_response_code(200);
         }
         echo json_encode($res); 
    }
    else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
        //Recibimos a travez del metodo put
        $postBody = file_get_contents("php://input");
        //Enviamos datos al manejador
        $res = $_productos->detectarDelete($postBody);
        //Devolvemos una respuesta
        header('content-type: application/json');
        if(isset($res["result"]["error_id"])){
            $responseCode = $res["result"]["error_id"];
            http_response_code($responseCode);
        }
        else{
            http_response_code(200);
        }
        echo json_encode($res); 
    }
    else{
        header('content-type: application/json');
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
    }
?>