<?php 
    //Se añaden los objetos que van a servir "/DBConnection.php"
    
    
    require_once('../dataBase/DBConnection.php');
    require_once("../dataBase/DBgetData.php");
    require_once("../models/queryCreator.php");
    require_once("../models/HTMLGenerator.php");

    //Se recibe la query
    $rawQuery = $_GET['q'];
   
    $ObjectConnection = new DBConnection();
    $ObjectGetData = new DBgetData();
    $rawQuery = strtolower($rawQuery);

    $connection = $ObjectConnection->get_connection();
 

    $ObjectQueryCreator = new QueryCreator();

    $query = $ObjectQueryCreator->builder($rawQuery);  
    $ArrayResult= $ObjectGetData->get_Array_Result_ASSOC($connection,$query);

    $ObjectGenerateTable = new HTMLGenerator();

    $template = $ObjectGenerateTable->generate_Table($ArrayResult);
        
    echo $template;
    
?>