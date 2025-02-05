<?php
    /* Mostrar errores */
    error_reporting(E_ALL); // Error/Exception engine, always use E_ALL
    ini_set('display_errors',1);
    ini_set('log_errors',1);
    ini_set("error_log","./php-error.log");
    
/*=============================================
CORS
=============================================*/

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('content-type: application/json; charset=utf-8');


    /*Requerimientos*/
   
    require_once "Helpers/Helpers.php";
    require_once 'controllers/routes.controller.php';

    $index=new RoutesController();
    $index->index();