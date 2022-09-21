<?php

    require_once "connection.php";
    require_once "get.model.php";

    class DeleteModel{

        /*==============================================================
            TODO: Peticion DELETE para eliminar datos de forma dinámica
        ==============================================================*/

        static public function deleteData($table, $id, $nameId){

            /*==============================================================
                TODO: Validar el ID
            ==============================================================*/

            $response = GetModel::getDataFilter($table, $nameId, $nameId, $id, null, null, null, null);

            if(empty($response)){


                return null;

            }

            /*==============================================================
                TODO: Eliminamos registros
            ==============================================================*/

            $sql = "DELETE FROM $table WHERE $nameId = :$nameId";

            $link = Connection::connect();
            $stmt = $link->prepare($sql);

            $stmt -> bindParam(":".$nameId, $id, PDO::PARAM_STR);

            if($stmt -> execute()){

                $response = array(

                    "comment" => "The pocess was successful"
                );

                return $response;

            }else{

                return $link->errorInfo();

            }



        }
    }

?>