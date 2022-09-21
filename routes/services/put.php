<?php

    require_once "models/connection.php";
    require_once "controllers/put.controller.php";

    if(isset($_GET["id"]) && isset($_GET["nameId"])){

        /*======================================================
            TODO: Capturamos datos del formulario
        ======================================================*/

        $data = array();
        parse_str(file_get_contents('php://input'), $data);

        /*======================================================
            TODO: Separar propiedades en un arreglo
        ======================================================*/

        $columns = array();

        foreach(array_keys($data) as $key => $value){

            array_push($columns, $value);

        }

        array_push($columns, $_GET["nameId"]);

        $columns = array_unique($columns);

        /*======================================================
            TODO: Validar la tabla y las columnas
        ======================================================*/

        if(empty(Connection::getColumnsData($table, $columns))){

            $json = array(
                'status' => 400,
                'results' => "Error: Fields in the form do not match the database"
            );

            echo json_encode($json, http_response_code($json["status"]));

            return;
        }

        if(isset($_GET["token"])){

            /*========================================================================================
                TODO: Petición PUT para usuarios no autorizados
            ========================================================================================*/

            if($_GET["token"] == "no" && isset($_GET["except"])){

                /*======================================================
                    TODO: Validar la tabla y las columnas
                ======================================================*/

                $columns = array($_GET["except"]);

                if(empty(Connection::getColumnsData($table, $columns))){

                    $json = array(
                        'status' => 400,
                        'results' => "Error: Fields in the form do not match the database"
                    );

                    echo json_encode($json, http_response_code($json["status"]));

                    return;
                }

                /*========================================================================================
                    TODO: Solicitamos respuesta del controlador para crear datos en cualquier tabla
                ========================================================================================*/

                $response = new PutController();
                $response -> putData($table, $data, $_GET["id"], $_GET["nameId"]);

            /*========================================================================================
                TODO: Petición PUT para usuarios autorizados
            ========================================================================================*/

            }else{

                $tableToken = $_GET["table"] ?? "users";
                $suffix = $_GET["suffix"] ?? "user";

                $validate = Connection::tokenValidate($_GET["token"], $tableToken, $suffix);

                /*========================================================================================
                    TODO: Solicitamos respuesta del controlador para editar datos en cualquier tabla
                ========================================================================================*/

                if($validate == "ok"){

                    $response = new PutController();
                    $response -> putData($table, $data, $_GET["id"], $_GET["nameId"]);

                }

                /*========================================================================================
                    TODO: Error cuando el Token ha expirado
                ========================================================================================*/

                if($validate == "expired"){

                    $json = array(
                        'status' => 303,
                        'results' => "Error: The token has expired"
                    );

                    echo json_encode($json, http_response_code($json["status"]));

                    return;

                }

                /*========================================================================================
                    TODO: Error cuando el Token no coincide en la base de datos
                ========================================================================================*/

                if($validate == "no-auth"){

                    $json = array(
                        'status' => 400,
                        'results' => "Error: The user is not authorized"
                    );

                    echo json_encode($json, http_response_code($json["status"]));

                    return;

                }

            }

        /*========================================================================================
            TODO: Error cuando no envía Token
        ========================================================================================*/

        }else{

            $json = array(
                'status' => 400,
                'results' => "Error: Authorization required"
            );

            echo json_encode($json, http_response_code($json["status"]));

            return;

        }

    }
?>