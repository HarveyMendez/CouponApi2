<?php
include 'data/Database.php';

header('Access-Control-Allow-Origin: *');

if($_POST['METHOD']=='POST'){

    unset($POST['METHOD']);
    $nombre_empresa=$_POST['nombre_empresa'];
    $nombre_usuario=$_POST['nombre_usuario'];
    $direccion_fisica=$_POST['direccion_fisica'];
    $cedula=$_POST['cedula'];
    $fecha_creacion=$_POST['fecha_creacion'];
    $correo_electronico=$_POST['correo_electronico'];
    $telefono=$_POST['telefono'];
    $contrasenna=$_POST['contrasenna'];
    $ubicacion=$_POST['ubicacion'];
    $estado=$_POST['estado'];
    $query="insert into Empresa(nombre_empresa, nombre_usuario, direccion_fisica, cedula, fecha_creacion, correo_electronico, telefono, contrasena, ubicacion, estado) values('$nombre_empresa', '$nombre_usuario', '$direccion_fisica', '$cedula', '$fecha_creacion', '$correo_electronico', '$contrasenna', '$ubicacion', '$estado')";
    $resultado=metodoPost($query);
    echo json_encode($resultado);
    //header("HTTP/1.1 200 ok");
    exit();

}

echo "¡Hola Mundo!";


?>