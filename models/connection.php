<?php

    require_once "vendor/autoload.php";
    use Firebase\JWT\JWT;

    require_once "get.model.php";

    class Connection{

        /*======================================================
            TODO: Información de la base de datos
        ======================================================*/

        static public function infoDatabase(){

            $infoDB = array(

                "database" => "bd_angelamaria",
                "user" => "root",
                "pass" => ""
            );

            return $infoDB;

        }

        /*======================================================
            TODO: APIKEY
        ======================================================*/

        static public function apikey(){

            return "YJEntU7gJwbnqeukvXxnRgNzA3jg9Q";
        }

        /*======================================================
            TODO: Acceso Público
        ======================================================*/

        static public function publicAccess(){

            $tables = ["products"];

            return $tables;

        }

        /*======================================================
            TODO: Conexión a la base de datos
        ======================================================*/

        static public function connect(){

            try{

                $link = new PDO("mysql:host=localhost;dbname=".Connection::infoDatabase()["database"],
                                Connection::infoDatabase()["user"],
                                Connection::infoDatabase()["pass"]);

                $link->exec("set names utf8");

            }catch(PDOException $e){

                die("Error: ".$e->getMessage());

            }

            return $link;

        }

        /*======================================================
            TODO: Validar existencia de una tabla en la BD
        ======================================================*/

        static public function getColumnsData($table, $columns){

            /*======================================================
                TODO: Traer el nombre de la base de datos
            ======================================================*/

            $database = Connection::infoDatabase()["database"];

            /*======================================================
                TODO: Traer todas las columnas de una tabla
            ======================================================*/

            $validate = Connection::connect()
            ->query("SELECT COLUMN_NAME AS item FROM information_schema.columns WHERE table_schema = '$database' AND table_name = '$table'")
            ->fetchAll(PDO::FETCH_OBJ);

            /*======================================================
                TODO: Validamos existencia de la tabla
            ======================================================*/

            if(empty($validate)){

                return null;

            }else{

                /*======================================================
                    TODO: Ajuste a solicitus de columnas globales
                ======================================================*/

                if($columns[0] == "*"){

                    array_shift($columns);

                }

                /*======================================================
                    TODO: Validamos existencia de columnas
                ======================================================*/

                $sum = 0;

                foreach($validate as $key => $value){

                    $sum += in_array($value -> item, $columns);

                }

                return $sum == count($columns) ? $validate : null;
            }
        }

        /*======================================================
            TODO: Generar Token de autenticación
        ======================================================*/

        static public function jwt($id, $email){

            $time = time();

            $token = array(

                "iat" => $time, //Tiempo en que inicia el token
                "exp" => $time + (60*60*24), //Tiempo en que expirará el token (1 día)
                "data" =>  [

                    "id" => $id,
                    "email" => $email
                ]
            );

            return $token;
        }

        /*======================================================
            TODO: Validar el token de seguridad
        ======================================================*/

        static public function tokenValidate($token, $table, $suffix){

            /*======================================================
                TODO: Traemos al usuario según el Token
            ======================================================*/

            $user = GetModel::getDataFilter($table, "token_exp_".$suffix, "token_".$suffix, $token, null, null, null, null);

            if(!empty($user)){

                /*======================================================
                    TODO: Validamos que el Token no haya expirado
                ======================================================*/

                $time = time();

                if($time < $user[0]->{"token_exp_".$suffix}){

                    return "ok";

                }else{

                    return "expired";

                }

            }else{

                return "no-auth";

            }
        }

    }

?>