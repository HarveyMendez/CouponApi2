<?php

$pdo=null;
$host="harveyucr.cjo4uuomk4or.us-east-1.rds.amazonaws.com:3306";
$user="UsuarioApi";
$password="apiUser123";
$bd="CouponDB";

function conectar() {
    try {
        $GLOBALS['pdo'] = new PDO("mysql:host=".$GLOBALS['host'].";dbname=".$GLOBALS['bd']."", $GLOBALS['user'], $GLOBALS['password']);
        $GLOBALS['pdo']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        print "Error! : No se pudo conectar a la BD ".$GLOBALS['bd']."<br/>";
        print "Error! : ".$e->getMessage()."<br/>";
        die();
    }
}

function desconectar(){

    $GLOBALS['pdo']=null;

}



function metodoPost($query, $queryAutoIncrement){

    try{
        conectar();
        $sentencia=$GLOBALS['pdo']->prepare($query);
        $sentencia->execute();
        $idAutoIncrement=metodoGet($queryAutoIncrement)->fetch(PDO::FETCH_ASSOC);
        $resultado=array_merge($idAutoIncrement, $_POST);
        $sentencia->closeCursor();
        desconectar();
        return $resultado;
    }
    catch(Exception $e){
        die("Error: ".$e);
    }
    
}




?>