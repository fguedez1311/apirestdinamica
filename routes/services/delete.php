<?php
     require_once 'models/connection.php';
     require_once 'controllers/delete.controller.php';

    if (isset($_GET["id"]) && isset($_GET["nameId"])){
        
        
        /*===============================================================
        Validar las tablas y las columnas
        =================================================================*/
        
        $columns=array($_GET["nameId"]);
        if (empty(Connection::getColumnData($table,$columns))){
            $json=array(
                'status'=>400,
                'results'=>"Error:Fields in the form do not match the database"
            
            );
            echo json_encode($json,http_response_code($json["status"]));
            return;
        }
        /*===============================================================
            Solicitamos respuesta del controlador para eliminar los datos en cualquier tabla
        =================================================================*/
        $response=new DeleteController();
        $response=DeleteController::deleteData($table,$_GET["id"],$_GET["nameId"]);
        
    }