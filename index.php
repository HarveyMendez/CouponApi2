<?php
//include 'business/EmpresaManager.php';
//include 'business/CouponManager.php';
include 'data/Database.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

//$empresaService = new EmpresaManager();
//$couponService = new CouponManager();
/*


if ($_SERVER['REQUEST_URI'] == '/index.php/insertEmpresa' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    $resultado = $empresaService->insertEmpresa($data);
    
    if (isset($resultado['error'])) {
        http_response_code(400);
        echo json_encode($resultado);
    } else {
        echo json_encode($resultado);
    }
    
    exit();
}

*/

if ($_SERVER['REQUEST_URI'] == '/index.php/newCoupon' && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

        
    if ($data === null) {
        // Error al decodificar JSON
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }

    $timestamp = date('YmdHis');

    // Parte aleatoria
    $randomString = substr(md5(uniqid(mt_rand(), true)), 0, 6);

    // Combina las dos partes
    $uniqueCode = $timestamp . $randomString;

    $nombre_usuario = $data['nombre_usuario'];
    $fecha_creacion = date('Y-m-d');
    $fecha_inicio = $data['fechaInicio'];
    $fecha_vencimiento = $data['fechaVencimiento'];
    $codigo = $uniqueCode;
    $nombre = $data['nombre'];
    $precio = $data['precio'];
    $estado = $data['estado'];
    $categoria = $data['categoria'];
    $cantidad = $data['cantidad'];
    $descuento = $data['descuento'];

    // Aquí deberías sanitizar y validar tus datos antes de usarlos en la consulta SQL

    $query = "INSERT INTO Cupones(usuarioEmpresa, fecha_creacion, fecha_inicio, fecha_vencimiento, nombre, precio, estado, categoria, cantidad, descuento, image, codigo) VALUES( '$nombre_usuario', '$fecha_creacion', '$fecha_inicio', '$fecha_vencimiento', '$nombre', '$precio', '$estado', '$categoria', '$cantidad', '$descuento', NULL, '$codigo')";
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

if ($_SERVER['REQUEST_URI'] == '/index.php/changeStatusCupon' && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

        
    if ($data === null) {
        // Error al decodificar JSON
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }

    $usuarioEmpresa = $data['nombre_usuario'];
    $nombreCupon = $data['nombre'];

    $query = "UPDATE Cupones 
                SET estado = NOT estado 
                WHERE nombre = '$nombreCupon' and usuarioEmpresa = '$usuarioEmpresa'";

    $resultado = metodoPut($query);
    echo json_encode($resultado);
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/index.php/changeStatusBusiness' && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

        
    if ($data === null) {
        // Error al decodificar JSON
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }

    $usuarioEmpresa = $data['nombre_usuario'];

    $query = "UPDATE Empresa 
                SET estado = NOT estado 
                WHERE nombre_usuario = '$usuarioEmpresa'";

    $resultado = metodoPut($query);
    echo json_encode($resultado);
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/index.php/cuponPurchase' && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

        
    if ($data === null) {
        // Error al decodificar JSON
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }

    $codigo = $data['codigo'];
    $cantidad = $data['cantidad'];

    $query = "UPDATE Cupones 
                SET cantidad = cantidad-$cantidad 
                WHERE codigo = '$codigo'";

    $resultado = metodoPut($query);
    echo json_encode($resultado);
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/index.php/updateBusiness' && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

        
    if ($data === null) {
        // Error al decodificar JSON
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }
    
    $nombre_usuario = $data['nombre_usuario'];
    $nombre_empresa = $data['nombre_empresa'];
    $direccion_fisica = $data['direccion_fisica'];
    $cedula = $data['cedula'];
    $correo_electronico = $data['correo_electronico'];
    $telefono = $data['telefono'];
    $ubicacion = $data['ubicacion'];


    //validar antes de hacer la consulta

    $query = "UPDATE Empresa 
                SET nombre_empresa = '$nombre_empresa', 
                    direccion_fisica = '$direccion_fisica', 
                    cedula = '$cedula', 
                    correo_electronico = '$correo_electronico', 
                    telefono = '$telefono', 
                    ubicacion = '$ubicacion'
                WHERE nombre_usuario = '$nombre_usuario'";

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

if ($_SERVER['REQUEST_URI'] == '/index.php/generateToken' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if ($data === null) {
        // Error al decodificar JSON
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }

    $nombre_usuario = $data['nombre_usuario']; 


    $query0 = "SELECT claveTemp from Claves 
                WHERE userEmpresa = '$nombre_usuario'";

    $resultado0 = metodoGet($query0);

    if ($resultado0 && $resultado0->rowCount() > 0) {
        echo json_encode($resultado0->fetchAll());
        exit();
    }

    
    $query1 = "SELECT id FROM Claves WHERE userEmpresa IS NULL ORDER BY RAND() LIMIT 1;";
    $resultado1 = metodoGet($query1);
    
    
    if (!$resultado1) {
        echo json_encode(['error' => 'Error al obtener el id aleatorio']);
        exit();
    }

    
    $row = $resultado1->fetch(PDO::FETCH_ASSOC);
    $id_aleatorio = $row['id'];

   
    $query2 = "UPDATE Claves 
                SET userEmpresa = '$nombre_usuario'
                WHERE id = '$id_aleatorio'";
    $resultado2 = metodoPut($query2);

    $query3 = "SELECT claveTemp FROM Claves
                    WHERE userEmpresa = '$nombre_usuario'";

    $resultado3 = metodoGet($query3);

    
    if ($resultado3) {
        echo json_encode($resultado3->fetchAll());
    } else {
        echo json_encode(['error' => 'Error al asignar el nombre de usuario al token']);
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
                    CASE
                        WHEN e.estado = false THEN NULL
                        ELSE e.nombre_usuario
                    END AS nombre_usuario,
                    CASE
                        WHEN e.estado = false THEN NULL
                        ELSE 
                            CASE
                                WHEN c.claveTemp = '$passwordBusiness' THEN c.claveTemp
                                WHEN e.contrasena = '$passwordBusiness' THEN e.contrasena
                                ELSE NULL
                            END
                    END AS contrasena,
                    CASE
                        WHEN e.estado = false THEN false
                        ELSE
                            CASE
                                WHEN c.claveTemp = '$passwordBusiness' THEN true
                                ELSE false
                            END
                    END AS token,
                    e.estado
                FROM (
                    SELECT nombre_usuario, contrasena, estado
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

/*
// Manejar solicitud para obtener cupones
if (strpos($_SERVER['REQUEST_URI'], '/index.php/getCoupon') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $usuarioEmpresa = $_GET['usuarioEmpresa'] ?? null;
    $resultado = $couponService->getCoupon($usuarioEmpresa);
    echo json_encode($resultado);
    exit();
}

// Manejar solicitud para obtener categorías
if (strpos($_SERVER['REQUEST_URI'], '/index.php/getCategories') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $idCategoria = $_GET['id'] ?? null;
    $resultado = $couponService->getCategories($idCategoria);
    echo json_encode($resultado);
    exit();
}

// Manejar solicitud para obtener empresas
if (strpos($_SERVER['REQUEST_URI'], '/index.php/getBusiness') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $usuarioEmpresa = $_GET['usuarioEmpresa'] ?? null;
    $resultado = $empresaService->getBusiness($usuarioEmpresa);
    echo json_encode($resultado);
    exit();
}
    

*/

// Manejar solicitud para obtener cupones de usuario
if (strpos($_SERVER['REQUEST_URI'], '/index.php/getUserCoupon') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = "SELECT 
                    c.id,
                    e.nombre_empresa,
                    e.ubicacion AS ubicacion_empresa,
                    c.nombre AS nombre_cupon,
                    c.cantidad,
                    c.precio,
                    c.codigo,
                    c.descuento,
                    c.fecha_vencimiento,
                    cat.nombreCategoria AS categoria,
                    c.image
                FROM 
                    Empresa e
                JOIN 
                    Cupones c ON e.nombre_usuario = c.usuarioEmpresa
                JOIN 
                    Categorias cat ON c.categoria = cat.id
                    WHERE c.estado=true and c.id = '$id'";
    $resultado = metodoGet($query);
    echo json_encode($resultado->fetchAll());
    } else {
        $query = "SELECT 
                    c.id,
                    e.nombre_empresa,
                    e.ubicacion AS ubicacion_empresa,
                    c.nombre AS nombre_cupon,
                    c.cantidad,
                    c.precio,
                    c.codigo,
                    c.descuento,
                    c.fecha_vencimiento,
                    cat.nombreCategoria AS categoria,
                    c.image
                FROM 
                    Empresa e
                JOIN 
                    Cupones c ON e.nombre_usuario = c.usuarioEmpresa
                JOIN 
                    Categorias cat ON c.categoria = cat.id
                    WHERE c.estado=true";
    $resultado = metodoGet($query);
    echo json_encode($resultado->fetchAll());

    }
    exit();
}








?>