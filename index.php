<?php
include 'data/Database.php';

header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD']=='POST'){
    $id=$_POST['id'];
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

    // Aquí deberías sanitizar y validar tus datos antes de usarlos en la consulta SQL

    $query="INSERT INTO Empresa VALUES('$id', '$nombre_empresa', '$nombre_usuario', '$direccion_fisica', '$cedula', '$fecha_creacion', '$correo_electronico', '$telefono', '$contrasenna', '$ubicacion', '$estado')";
    $resultado=metodoPost($query);
    echo json_encode($resultado);
    exit();
}

if($_SERVER['REQUEST_METHOD']=='GET'){
    if(isset($_GET['id'])){
        $id = $_GET['id'];

        // Aquí deberías validar y escapar la entrada para prevenir inyecciones SQL

        echo "devolver uno";

        $query = "SELECT * FROM Empresa WHERE id=?";
        $resultado = metodoGet($query, [$id]);
        echo json_encode($resultado);
        exit();
    }
    else{
        echo "devolver todo";
        $query = "SELECT * FROM Empresa";
        $resultado = metodoGet($query);
        echo json_encode($resultado);
        exit();
    }
    echo "no devolver nada";
}

echo "¡Hola Mundo!";


?>