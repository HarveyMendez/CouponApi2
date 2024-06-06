<?php
include 'data/Database.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_URI'] == '/index.php/insertEmpresa' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre_empresa = $_POST['nombre_empresa'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $direccion_fisica = $_POST['direccion_fisica'];
    $cedula = $_POST['cedula'];
    $fecha_creacion = $_POST['fecha_creacion'];
    $correo_electronico = $_POST['correo_electronico'];
    $telefono = $_POST['telefono'];
    $contrasenna = $_POST['contrasenna'];
    $ubicacion = $_POST['ubicacion'];
    $estado = $_POST['estado'];

    // Aquí deberías sanitizar y validar tus datos antes de usarlos en la consulta SQL

    $query = "INSERT INTO Empresa VALUES('$id', '$nombre_empresa', '$nombre_usuario', '$direccion_fisica', '$cedula', '$fecha_creacion', '$correo_electronico', '$telefono', '$contrasenna', '$ubicacion', '$estado')";
    $resultado = metodoPost($query);
    echo json_encode($resultado);
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/index.php/newCoupon' && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

        
    if ($data === null) {
        // Error al decodificar JSON
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }


    $id = $data['id'];
    $nombre_usuario = $data['nombre_usuario'];
    $fecha_creacion = date('Y-m-d');
    $fecha_inicio = $data['fechaInicio'];
    $fecha_vencimiento = $data['fechaVencimiento'];
    $nombre = $data['nombre'];
    $precio = $data['precio'];
    $estado = $data['estado'];
    $categoria = $data['categoria'];
    $cantidad = $data['cantidad'];

    // Aquí deberías sanitizar y validar tus datos antes de usarlos en la consulta SQL

    $query = "INSERT INTO Cupones(usuarioEmpresa, fecha_creacion, fecha_inicio, fecha_vencimiento, nombre, precio, estado, categoria, cantidad) VALUES( '$nombre_usuario', '$fecha_creacion', '$fecha_inicio', '$fecha_vencimiento', '$nombre', '$precio', '$estado', '$categoria', '$cantidad')";
    $resultado = metodoPost($query);
    echo json_encode($resultado);
    exit();
}


if ($_SERVER['REQUEST_URI'] == '/index.php/businessLogin' && $_SERVER['REQUEST_METHOD'] == 'POST') {

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        
        if ($data === null) {
            // Error al decodificar JSON
            http_response_code(400); 
            echo json_encode(['error' => 'Error en los datos JSON']);
            exit();
        }

        $usernameBusiness = $data['username']; 
        $passwordBusiness = $data['password']; 

        

        $query = "SELECT 
                    nombre_usuario,
                    CASE
                        WHEN e.contrasena = '$passwordBusiness' THEN e.contrasena
                    END AS contrasena
                FROM (
                    SELECT nombre_usuario, contrasena
                    FROM Empresa
                    WHERE nombre_usuario = '$usernameBusiness'
                ) AS e;";

        

        $resultado = metodoGet($query);

        if ($resultado === false) {
            echo json_encode(['nombre_usuario' => '', 'contrasenna' => '']);
        } else {
            echo json_encode($resultado->fetch(PDO::FETCH_ASSOC));
        }

        //
        exit();
}




if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {

        // Aquí deberías validar y escapar la entrada para prevenir inyecciones SQL

        $query = "SELECT * FROM Empresa WHERE id=" . $_GET['id'];
        $resultado = metodoGet($query);
        echo json_encode($resultado->fetch(PDO::FETCH_ASSOC));
    } else {
        $query = "SELECT * FROM Empresa";
        $resultado = metodoGet($query);
        echo json_encode($resultado->fetchAll());

    }
    exit();
}

echo "¡Hola Mundo!";


?>