<?php
     require_once 'models/connection.php';
     require_once 'controllers/put.controller.php';

    if (isset($_GET["id"]) && isset($_GET["nameId"])){
        
        /*===============================================================
        Capturamos los datos del formulario
        =================================================================*/
        $data=array();
        parse_str(file_get_contents('php://input'),$data);
        /*===============================================================
        Validar las tablas y las columnas
        =================================================================*/
        
        $columns=array();
        foreach (array_keys($data) as $key => $value) {
            array_push($columns,$value);
        }
        
        array_push($columns,$_GET["nameId"]);
        array_unique($columns);

        if (empty(Connection::getColumnData($table,$columns))){
            $json=array(
                'status'=>400,
                'results'=>"Error:Fields in the form do not match the database"
            
            );
            echo json_encode($json,http_response_code($json["status"]));
            return;
        }
        /*===============================================================
            Solicitamos respuesta del controlador para actualizar los datos en cualquier tabla
        =================================================================*/
        $response=PutController::putData($table,$data,$_GET["id"],$_GET["nameId"]);
        
    }