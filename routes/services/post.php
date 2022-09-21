<?php

    require_once "models/connection.php";
    require_once "controllers/post.controller.php";

    if(isset($_POST)){

        /*======================================================
            TODO: Separar propiedades en un arreglo
        ======================================================*/

        $columns = array();

        foreach(array_keys($_POST) as $key => $value){

            array_push($columns, $value);

        }

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

        $response = new PostController();

        /*========================================================================================
            TODO: Petición POST para registrar usuario
        ========================================================================================*/

        if(isset($_GET["register"]) && $_GET["register"] == true){

            $suffix = $_GET["suffix"] ?? "user";

            $response -> postRegister($table, $_POST, $suffix);

        /*========================================================================================
            TODO: Petición POST para Login de usuario
        ========================================================================================*/

        }else if(isset($_GET["login"]) && $_GET["login"] == true){

            $suffix = $_GET["suffix"] ?? "user";

            $response -> postLogin($table, $_POST, $suffix);


        }else{

            if(isset($_GET["token"])){

                /*========================================================================================
                    TODO: Petición POST para usuarios no autorizados
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

                    $response -> postData($table, $_POST);

                /*========================================================================================
                    TODO: Petición POST para usuarios autorizados
                ========================================================================================*/

                }else{

                    $tableToken = $_GET["table"] ?? "users";
                    $suffix = $_GET["suffix"] ?? "user";

                    $validate = Connection::tokenValidate($_GET["token"], $tableToken, $suffix);

                    /*========================================================================================
                        TODO: Solicitamos respuesta del controlador para crear datos en cualquier tabla
                    ========================================================================================*/

                    if($validate == "ok"){

                        $response -> postData($table, $_POST);

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

    }

?>