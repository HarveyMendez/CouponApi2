<?php
include 'data/Database.php';


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

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

    $nombre_usuario = $data['nombre_usuario'];
    $fecha_creacion = date('Y-m-d');
    $fecha_inicio = $data['fechaInicio'];
    $fecha_vencimiento = $data['fechaVencimiento'];
    $nombre = $data['nombre'];
    $precio = $data['precio'];
    $estado = $data['estado'];
    $categoria = $data['categoria'];
    $cantidad = $data['cantidad'];
    $descuento = $data['descuento'];

    // Aquí deberías sanitizar y validar tus datos antes de usarlos en la consulta SQL

    $query = "INSERT INTO Cupones(usuarioEmpresa, fecha_creacion, fecha_inicio, fecha_vencimiento, nombre, precio, estado, categoria, cantidad, descuento) VALUES( '$nombre_usuario', '$fecha_creacion', '$fecha_inicio', '$fecha_vencimiento', '$nombre', '$precio', '$estado', '$categoria', '$cantidad', '$descuento')";
    $resultado = metodoPost($query);
    echo json_encode($resultado);
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/index.php/updateCupon' && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

        
    if ($data === null) {
        // Error al decodificar JSON
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }
    
    $nombre_usuario = $data['nombre_usuario'];
    $fecha_inicio = $data['fechaInicio'];
    $fecha_vencimiento = $data['fechaVencimiento'];
    $nombre = $data['nombre'];
    $precio = $data['precio'];
    $estado = $data['estado'];
    $categoria = $data['categoria'];
    $cantidad = $data['cantidad'];
    $descuento = $data['descuento'];

    //validar antes de hacer la consulta

    $query = "UPDATE Cupones 
                SET fecha_inicio = '$fecha_inicio', 
                    fecha_vencimiento = '$fecha_vencimiento', 
                    nombre = '$nombre', 
                    precio = '$precio', 
                    estado = '$estado', 
                    categoria = '$categoria', 
                    cantidad = '$cantidad', 
                    descuento = '$descuento'
                WHERE usuarioEmpresa = '$nombre_usuario' 
                    AND nombre = '$nombre'";
    $resultado = metodoPut($query);
    echo json_encode($resultado);
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/index.php/changePassword' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if ($data === null) {
        // Error al decodificar JSON
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }

    $usernameBusiness = $data['username']; 
    $newPasswordBusiness = $data['password'];

    $query1 = "UPDATE Empresa 
                SET contrasena = '$newPasswordBusiness'
                WHERE nombre_usuario = '$usernameBusiness'";

    $query2 = "UPDATE Claves 
                SET userEmpresa = NULL 
                WHERE userEmpresa = '$usernameBusiness'";

    $resultado1 = metodoGet($query1);

    $resultado2 = metodoGet($query2);

        if ($resultado1 === false || $resultado2 === false) {
            echo json_encode(['nombre_usuario' => '', 'contrasenna' => '']);
        } else {
            echo json_encode(['message' => 'Contraseña actualizada y usuario desvinculado correctamente']);
        }

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
    e.nombre_usuario,
    CASE
	    WHEN c.claveTemp = '$passwordBusiness' THEN c.claveTemp
        WHEN e.contrasena = '$passwordBusiness' THEN e.contrasena
        ELSE NULL
    END AS contrasena,
    CASE
    	WHEN c.claveTemp = '$passwordBusiness' THEN true
    	ELSE false
    END AS token
FROM (
    SELECT nombre_usuario, contrasena
    FROM Empresa
    WHERE nombre_usuario = '$usernameBusiness'
) AS e
	LEFT JOIN Claves c ON c.userEmpresa = e.nombre_usuario;";

        

        $resultado = metodoGet($query);

        if ($resultado === false) {
            echo json_encode(['nombre_usuario' => '', 'contrasenna' => '']);
        } else {
            echo json_encode($resultado->fetch(PDO::FETCH_ASSOC));
        }

        //
        exit();
}

if (strpos($_SERVER['REQUEST_URI'], '/index.php/getCoupon') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    
    if (isset($_GET['usuarioEmpresa'])) {
        
        // Aquí deberías validar y escapar la entrada para prevenir inyecciones SQL
        $usuarioEmpresa = $_GET['usuarioEmpresa'];
        $query = "SELECT * FROM Cupones WHERE usuarioEmpresa='$usuarioEmpresa'";
        $resultado = metodoGet($query);
        echo json_encode($resultado->fetchAll());
    } else {
        $query = "SELECT * FROM Cupones";
        $resultado = metodoGet($query);
        echo json_encode($resultado->fetchAll());

    }
    exit();
}

if (strpos($_SERVER['REQUEST_URI'], '/index.php/getCategories') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    
    if (isset($_GET['id'])) {
        
        // Aquí deberías validar y escapar la entrada para prevenir inyecciones SQL
        $idCategoria = $_GET['id'];
        $query = "SELECT * FROM Categorias WHERE id='$id'";
        $resultado = metodoGet($query);
        echo json_encode($resultado->fetchAll());
    } else {
        $query = "SELECT * FROM Categorias";
        $resultado = metodoGet($query);
        echo json_encode($resultado->fetchAll());

    }
    exit();
}

echo "¡Hola Mundo!";




?>