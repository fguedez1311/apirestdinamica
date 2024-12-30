<?php
    /* Mostrar errores */
    error_reporting(E_ALL); // Error/Exception engine, always use E_ALL
    ini_set('display_errors',1);
    ini_set('log_errors',1);
    ini_set("error_log","./php-error.log");

    /*Requerimientos*/
   
    require_once "Helpers/Helpers.php";
    require_once 'controllers/routes.controller.php';

    $index=new RoutesController();
    $index->index();