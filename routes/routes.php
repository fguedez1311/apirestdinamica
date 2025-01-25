<?php
   
   $routesArray=explode("/",$_SERVER['REQUEST_URI']);
   $routesArray=array_filter($routesArray);

   /*===============================================================
   Cuando no se hace petición a la API
   =================================================================*/
   if (count($routesArray)==0){
    $json=array(
        'status'=>404,
        'result'=>'Not found'
    
       );
       echo json_encode($json,http_response_code($json["status"]));
       return;
   }
   
   /*===============================================================
   Cuando si se hace una petición a la API
   =================================================================*/

   if (count($routesArray)==1 && isset($_SERVER['REQUEST_METHOD'])){

        $table=explode("?",$routesArray[1])[0];
        /*===============================================================
        Petición de Tipo GET
        =================================================================*/
        if ($_SERVER['REQUEST_METHOD']==="GET"){
          include 'services/get.php';
                       
        }
         /*===============================================================
        Petición de Tipo POST
        =================================================================*/
        if ($_SERVER['REQUEST_METHOD']==="POST"){
            include 'services/post.php';
                       
        }
         /*===============================================================
        Petición de Tipo PUT
        =================================================================*/
        if ($_SERVER['REQUEST_METHOD']==="PUT"){
            include 'services/put.php';
                       
        }
         /*===============================================================
        Petición de Tipo DELETE
        =================================================================*/
        if ($_SERVER['REQUEST_METHOD']==="DELETE"){
           include 'services/delete.php';
                       
        }
   }
  