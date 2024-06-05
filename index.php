<?php
include 'data/Database.php';

header('Access-Control-Allow-Origin: *');

if($_POST['METHOD']=='POST'){

    unset($POST['METHOD']);
    $nombre=$_POST['nombre'];
    $lanzamiento=$_POST['lanzamiento'];
    $desarrollador=$_POST['desarrollador'];
    $query="insert into frameworks(nombre,lanzamiento,desarrollador) values('$nombre','$lanzamiento','$desarrollador')";
    $queryAutoIncrement="Select MAX(id) as id from frameworks";
    $resultado=metodoPost($query, $queryAutoIncrement);
    echo json_encode($resultado);
    header("HTTP/1.1 200 ok");
    exit();

}

echo "¡Hola Mundo!";


?>