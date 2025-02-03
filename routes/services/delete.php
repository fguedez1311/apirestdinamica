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
                Peticiones DELETE para usuarios autorizados
        =================================================================*/
        if (isset($_GET["token"])){
            /*===============================================================
                    Peticiones PUT para usuarios autorizados
            =================================================================*/
            $tableToken=$_GET["table"] ?? "users";
            $suffix=$_GET["suffix"] ?? "user";
            $validate=Connection::tokenValidate($_GET["token"],$tableToken,$suffix);
            /*===============================================================
                Solicitamos respuesta del controlador para eliminar los datos en cualquier tabla
            =================================================================*/
            if ($validate=="ok"){
                $response=new DeleteController();
                $response=DeleteController::deleteData($table,$_GET["id"],$_GET["nameId"]);
            }
             /*===============================================================
                Error cuando el token ha expirado
                =================================================================*/
                if ($validate=="expired"){
                    $json=array(
                        'status'=>303,
                        'results'=>"Error:the token has expired"
                    
                    );
                    echo json_encode($json,http_response_code($json["status"]));
                    return;
                }
                /*===============================================================
                Error cuando el token no coinciden en Base de Datos
                =================================================================*/
                if ($validate=="no-auth"){
                    $json=array(
                        'status'=>400,
                        'results'=>"Error:the user is not authorized"
                    
                    );
                    echo json_encode($json,http_response_code($json["status"]));
                    return;
                }
           
        }
         /*===============================================================
        Error Cuando no envia token
        =================================================================*/
        else{
            $json=array(
                'status'=>400,
                'results'=>"Authorization required"
            
            );
            echo json_encode($json,http_response_code($json["status"]));
            return;
        }
        
        
    }