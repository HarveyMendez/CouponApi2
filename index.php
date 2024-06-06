<?php
include 'data/Database.php';

header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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


if ($_SERVER['REQUEST_URI'] == '/businessLogin') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        
        if ($data === null) {
            // Error al decodificar JSON
            http_response_code(400); 
            echo json_encode(['error' => 'Error en los datos JSON']);
            exit();
        }

        $username = $data['username']; 
        $password = $data['password']; 

        $query = "SELECT 
                    nombre_usuario,
                    CASE
                        WHEN e.contrasena = '$password' THEN e.contrasena
                    END AS contrasena
                FROM (
                    SELECT nombre_usuario, contrasena
                    FROM Empresa
                    WHERE nombre_usuario = '$username'
                ) AS e;";

        $resultado = metodoPost($query);
        echo json_encode($resultado);
        exit();
    }
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