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
      
        if (isset($_GET["token"])){
                 /*===============================================================
                Peticiones PUT para usuarios no autorizados
                =================================================================*/
                
                if ($_GET["token"]=="no" && isset($_GET["except"]) ){
                    /*===============================================================
                    Validar las tablas y las columnas
                    =================================================================*/
                    $columns=array($_GET["except"]);
                    if (empty(Connection::getColumnData($table,$columns))){
                        $json=array(
                            'status'=>400,
                            'results'=>"Error:Fields in the form do not match the database"
                        
                        );
                        echo json_encode($json,http_response_code($json["status"]));
                        return;
                    }
                    /*===============================================================
                        Solicitamos respuesta del controlador para crear datos en cualquier tabla
                    =================================================================*/
            
                    $response=new PutController();
                    $response=PutController::putData($table,$data,$_GET["id"],$_GET["nameId"]);

                }
                /*===============================================================
                Peticiones PUT para usuarios autorizados
                =================================================================*/
                else{
                    $tableToken=$_GET["table"] ?? "users";
                    $suffix=$_GET["suffix"] ?? "user";
                    $validate=Connection::tokenValidate($_GET["token"],$tableToken,$suffix);
                    /*===============================================================
                        Solicitamos respuesta del controlador para actualizar los datos en cualquier tabla
                    =================================================================*/
                    if ($validate=="ok"){
                        
                        $response=new PutController();
                        $response=PutController::putData($table,$data,$_GET["id"],$_GET["nameId"]);
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