<?php

    require_once 'models/post.model.php';
    class PostController{

        /*===============================================================
        Petición POST para crear datos
        =================================================================*/
        static public function postData($table,$data){
            $response=PostModel::postData($table,$data);
            $return =new PostController();
            $return ->fncResponse($response);
        }
         /*===============================================================
        Respuesta del controlador
        =================================================================*/

        public function fncResponse($response){

            if(!empty($response)){
                $json=array(
                    'status'=>200,
                    'results'=>$response
                
                );
            }
            else{
                $json=array(
                    'status'=>404,
                    'results'=>'NotFound',
                    'method'=>'post'
                
                );
            }

           
            echo json_encode($json,http_response_code($json["status"]));
        }
    }