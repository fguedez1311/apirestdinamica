<?php

    require_once 'models/post.model.php';
    require_once 'models/get.model.php';
    require_once 'models/put.model.php';
    require_once 'models/connection.php';
    require_once "vendor/autoload.php";
    use Firebase\JWT\JWT;
    
    class PostController{

        /*===============================================================
        Petición POST para crear datos
        =================================================================*/
        static public function postData($table,$data){
            $response=PostModel::postData($table,$data);
            $return =new PostController();
            $return ->fncResponse($response,null,null);
        }
         /*===============================================================
        Petición POST para registrar datos
        =================================================================*/
        static public function postRegister($table,$data,$suffix){
            if (isset($data["password_".$suffix]) && $data["password_".$suffix]!=null){
                $crypt=crypt($data["password_".$suffix],'$2a$07$franomesillystringforsalt$');
                $data["password_".$suffix]=$crypt;
               
                $response=PostModel::postData($table,$data);
                $return =new PostController();
                $return ->fncResponse($response,null,$suffix);
                
                
            }
            else{
                /*===============================================================
                Registro de usuario desde APP externa
                =================================================================*/
                $response=PostModel::postData($table,$data);

                if (isset($response["comment"]) && $response["comment"]=="The process was successful"){
                      /*===============================================================
                         Validar que el  usuario exista en BD
                      =================================================================*/
                    $response=GetModel::getDataFilter($table,"*","email_".$suffix,$data["email_".$suffix],null,null,null,null);
                    if (!empty($response)){
                       
                        $token=Connection::jwt($response[0]->{"id_".$suffix},$response[0]->{"email_".$suffix});
                        $jwt=JWT::encode($token,"sddfgh34sssswee453ggh",'HS256');
                    
                        /*===============================================================
                        Actualizar la Base de datos con el token del usuario
                        =================================================================*/
                        $data=array(
                            "token_".$suffix=>$jwt,
                            "token_exp_".$suffix=>$token["exp"]
                        );
                    
                        $update=PutModel::putData($table,$data,$response[0]->{"id_".$suffix},"id_".$suffix);
                        
                        if (isset($update["comment"]) && $update["comment"]=="The process was successful"){
                            $response[0]->{"token_".$suffix} = $jwt;
						    $response[0]->{"token_exp_".$suffix} = $token["exp"];

						    $return = new PostController();
						    $return -> fncResponse($response, null,$suffix);
                        }

                    }
                    
                }
            }
        }
         /*===============================================================
        Petición POST para login  de usuario
        =================================================================*/
        static public function postLogin($table,$data,$suffix){
             /*===============================================================
            Validar que el  usuario exista en BD
            =================================================================*/
            $response=GetModel::getDataFilter($table,"*","email_".$suffix,$data["email_".$suffix],null,null,null,null);
            
            if (!empty($response)){
                
                if($response[0]->{"password_".$suffix} != null){
                    /*===============================================================
                    Encriptamos la contraseña
                    =================================================================*/
                    $crypt=crypt($data["password_".$suffix],'$2a$07$franomesillystringforsalt$');

                    if($response[0]->{"password_".$suffix}==$crypt){
                        $token=Connection::jwt($response[0]->{"id_".$suffix},$response[0]->{"email_".$suffix});
                        $jwt=JWT::encode($token,"sddfgh34sssswee453ggh",'HS256');
                        
                        /*===============================================================
                        Actualizar la Base de datos con el token del usuario
                        =================================================================*/
                        $data=array(
                            "token_".$suffix=>$jwt,
                            "token_exp_".$suffix=>$token["exp"]
                        );
                        
                        $update=PutModel::putData($table,$data,$response[0]->{"id_".$suffix},"id_".$suffix);
                    
                        if (isset($update["comment"]) && $update["comment"]=="The process was successful"){
                            $response[0]->{"token_".$suffix} = $jwt;
                            $response[0]->{"token_exp_".$suffix} = $token["exp"];
                            $return = new PostController();
                            $return -> fncResponse($response, null,$suffix);
                        }
                        
                    }
                    else {
                        $response=null;
                        $return=new PostController();
                        $return->fncResponse($response,"Wrong password",$suffix);
                    }

                }
                else{
                    /*===============================================================
                    Actualizamos el token para usuarios logueados desde app externas
                    =================================================================*/
                    $token=Connection::jwt($response[0]->{"id_".$suffix},$response[0]->{"email_".$suffix});
                    $jwt=JWT::encode($token,"sddfgh34sssswee453ggh",'HS256');
                    
                    /*===============================================================
                    Actualizar la Base de datos con el token del usuario
                    =================================================================*/
                    $data=array(
                        "token_".$suffix=>$jwt,
                        "token_exp_".$suffix=>$token["exp"]
                    );
                    
                    $update=PutModel::putData($table,$data,$response[0]->{"id_".$suffix},"id_".$suffix);
                
                    if (isset($update["comment"]) && $update["comment"]=="The process was successful"){
                        $response[0]->{"token_".$suffix} = $jwt;
                        $response[0]->{"token_exp_".$suffix} = $token["exp"];
                        $return = new PostController();
                        $return -> fncResponse($response, null,$suffix);
                    }
                } 
                
              
            }
            else{
                
                $response=null;
                $return=new PostController();
                $return->fncResponse($response,"Wrong email",$suffix);

            }
        }
         /*===============================================================
        Respuesta del controlador
        =================================================================*/

        public function fncResponse($response,$error,$suffix){

            if(!empty($response)){
                /*===============================================================
                Quitamos la contraseña de la respuesta
                =================================================================*/
                if (isset($response[0]->{"password_".$suffix})){
                    unset($response[0]->{"password_".$suffix});
                }
                $json=array(
                    'status'=>200,
                    'results'=>$response
                
                );
            }
            else{
                if ($error!=null){
                    $json=array(
                        'status'=>400,
                        'results'=>$error,
                    );
                }
                else {

                        $json=array(
                            'status'=>404,
                            'results'=>'NotFound',
                            'method'=>'post'
                        
                        );

                }
               
            }

           
            echo json_encode($json,http_response_code($json["status"]));
        }
    }