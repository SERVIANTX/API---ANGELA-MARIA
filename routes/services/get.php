<?php

    require_once "controllers/get.controller.php";

    $select = $_GET["select"] ?? "*";
    $orderBy = $_GET["orderBy"] ?? NULL;
    $orderMode = $_GET["orderMode"] ?? NULL;
    $startAt = $_GET["startAt"] ?? NULL;
    $endAt = $_GET["endAt"] ?? NULL;
    $filterTo = $_GET["filterTo"] ?? NULL;
    $inTo = $_GET["inTo"] ?? NULL;

    $response = new GetController();

    /*======================================================
        TODO: Peticions GET con filtro
    ======================================================*/

    if(isset($_GET["linkTo"]) && isset($_GET["equalTo"]) && !isset($_GET["rel"]) && !isset($_GET["type"])){

        $response -> getDataFilter($table, $select, $_GET["linkTo"], $_GET["equalTo"], $orderBy, $orderMode, $startAt, $endAt);

    /*==============================================================
        TODO: Peticions GET sin filtro entre tablas relacionadas
    ==============================================================*/

    }else if(isset($_GET["rel"]) && isset($_GET["type"]) && $table == "relations" && !isset($_GET["linkTo"]) && !isset($_GET["equalTo"])){

        $response -> getRelData($_GET["rel"], $_GET["type"], $select, $orderBy, $orderMode, $startAt, $endAt);

    /*==============================================================
        TODO: Peticions GET con filtro entre tablas relacionadas
    ==============================================================*/

    }else if(isset($_GET["rel"]) && isset($_GET["type"]) && $table == "relations" && isset($_GET["linkTo"]) && isset($_GET["equalTo"])){

        $response -> getRelDataFilter($_GET["rel"], $_GET["type"], $select, $_GET["linkTo"], $_GET["equalTo"], $orderBy, $orderMode, $startAt, $endAt);

    /*==============================================================
        TODO: Peticions GET para el buscador sin relaciones
    ==============================================================*/

    }else if(!isset($_GET["rel"]) && !isset($_GET["type"]) && isset($_GET["linkTo"]) && isset($_GET["search"])){

        $response -> getDataSearch($table, $select, $_GET["linkTo"], $_GET["search"], $orderBy, $orderMode, $startAt, $endAt);

    /*==============================================================
        TODO: Peticions GET para el buscador con relaciones
    ==============================================================*/

    }else if(isset($_GET["rel"]) && isset($_GET["type"]) && $table == "relations" && isset($_GET["linkTo"]) && isset($_GET["search"])){

        $response -> getRelDataSearch($_GET["rel"], $_GET["type"], $select, $_GET["linkTo"], $_GET["search"], $orderBy, $orderMode, $startAt, $endAt);

    /*==============================================================
        TODO: Peticions GET para selección de rangos
    ==============================================================*/

    }else if(!isset($_GET["rel"]) && !isset($_GET["type"]) && isset($_GET["linkTo"]) && isset($_GET["between1"]) && isset($_GET["between2"])){

        $response -> getDataRange($table, $select, $_GET["linkTo"], $_GET["between1"], $_GET["between2"], $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo);

    /*====================================================================
        TODO: Peticions GET para selección de rangos con relaciones
    ====================================================================*/

    }else if(isset($_GET["rel"]) && isset($_GET["type"]) && $table == "relations" && isset($_GET["linkTo"]) && isset($_GET["between1"]) && isset($_GET["between2"])){

        $response -> getRelDataRange($_GET["rel"], $_GET["type"], $select, $_GET["linkTo"], $_GET["between1"], $_GET["between2"], $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo);

    }else{

        /*======================================================
            TODO: Peticiones GET sin filtro
        ======================================================*/

        $response -> getData($table, $select, $orderBy, $orderMode, $startAt, $endAt);

    }


?>