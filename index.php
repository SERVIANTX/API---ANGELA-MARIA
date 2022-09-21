<?php

    /*=============================================
        TODO: Zona Horaria
    =============================================*/

    date_default_timezone_set('America/Lima');

    /*=============================================
        TODO: Mostrar errores
    =============================================*/

    ini_set('display_errors', 1);
    ini_set("log_errors", 1);
    ini_set("error_log",  "D:/xampp/htdocs/Proyecto_modular/api-angelamaria/php_error_log");

    /*=============================================
        TODO: CORS
    =============================================*/

    /* TODO: Permitir el acceso de otro origen */

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('content-type: application/json; charset=utf-8');

    /*=============================================
        TODO: Requerimientos
    =============================================*/

    require_once "controllers/routes.controller.php";

    $index = new RoutesController();
    $index -> index();

?>