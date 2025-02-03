<?php
        require_once 'models/connection.php';
        require_once 'controllers/post.controller.php';

        if (isset($_POST)){
            
            $columns=array();
            foreach (array_keys($_POST) as $key => $value) {
                array_push($columns,$value);
            }
            /*===============================================================
            Validar las tablas y las columnas
            =================================================================*/
            
            if (empty(Connection::getColumnData($table,$columns))){
                $json=array(
                    'status'=>400,
                    'results'=>"Error:Fields in the form do not match the database"
                
                );
                echo json_encode($json,http_response_code($json["status"]));
                return;
            }

            $response=new PostController();

            /*===============================================================
            Petición Post para un registro de usuario
            =================================================================*/
            if (isset($_GET["register"]) && $_GET["register"]==true){
                $suffix=$_GET["suffix"] ?? "user";
                $response=PostController::postRegister($table,$_POST,$suffix);
            }
             /*===============================================================
            Petición Post para un Login de usuario
            =================================================================*/
            else if (isset($_GET["login"]) && $_GET["login"]==true){
                $suffix=$_GET["suffix"] ?? "user";
                $response=PostController::postLogin($table,$_POST,$suffix);
            }
            else{
              
                if (isset($_GET["token"])){
                    /*===============================================================
                        Peticiones POST para usuarios no autorizados
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
                
                        $response=PostController::postData($table,$_POST);

                    }
                     /*===============================================================
                        Peticiones POST para usuarios  autorizados
                    =================================================================*/
                    else{
                        $tableToken=$_GET["table"] ?? "users";
                        $suffix=$_GET["suffix"] ?? "user";
                        $validate=Connection::tokenValidate($_GET["token"],$tableToken,$suffix);
                        if ($validate=="ok"){
                            /*===============================================================
                                Solicitamos respuesta del controlador para crear datos en cualquier tabla
                            =================================================================*/
                    
                            $response=PostController::postData($table,$_POST);
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
                    Error cuando no envia token
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
            
            
        }
        