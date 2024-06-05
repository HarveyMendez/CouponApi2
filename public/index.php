<?php
require '../vendor/autoload.php';
require '../business/EmpresaManager.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_empresa = $_POST['nombre_empresa'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $direccion_fisica = $_POST['direccion_fisica'];
    $cedula = $_POST['cedula'];
    $fecha_creacion = $_POST['fecha_creacion'];
    $correo_electronico = $_POST['correo_electronico'];
    $telefono = $_POST['telefono'];
    $contrasena = $_POST['contrasena'];
    $ubicacion = $_POST['ubicacion'];
    $estado = $_POST['estado'];

    $empresaManager = new EmpresaManager();
    $result = $empresaManager->insertarEmpresa($nombre_empresa, $nombre_usuario, $direccion_fisica, $cedula, $fecha_creacion, $correo_electronico, $telefono, $contrasena, $ubicacion, $estado);

    echo json_encode($result);
} else {
    echo json_encode(array("status" => "error", "message" => "Metodo de request invalido"));
}
?>