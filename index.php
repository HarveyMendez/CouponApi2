<?php
include_once 'business/EmpresaManager.php';
include_once 'business/CouponManager.php';
include_once 'data/Database.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

$empresaManager = new EmpresaManager();
$couponManager = new CouponManager();

if ($_SERVER['REQUEST_URI'] == '/index.php/insertEmpresa' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    $resultado = $empresaManager->insertEmpresa($data);
    
    if (isset($resultado['error'])) {
        http_response_code(400);
        echo json_encode($resultado);
    } else {
        echo json_encode($resultado);
    }
    
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/index.php/newCoupon' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del cuerpo de la solicitud
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Validar el JSON
    if ($data === null) {
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }

    // Llamar al método de la capa de negocio para agregar un nuevo cupón
    $resultado = $couponManager->agregarCupon($data);

    echo json_encode($resultado);
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/index.php/updateCupon' && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }

    $resultado = $couponManager->actualizarCupon($data);

    echo json_encode($resultado);
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/index.php/changeStatusCupon' && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }

    $resultado = $couponManager->cambiarEstadoCupon($data);

    echo json_encode($resultado);
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/index.php/changeStatusBusiness' && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }

    $resultado = $empresaManager->cambiarEstadoEmpresa($data);

    echo json_encode($resultado);
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/index.php/cuponPurchase' && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }

    $resultado = $couponManager->comprarCupon($data);

    echo json_encode($resultado);
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/index.php/updateBusiness' && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }

    $resultado = $empresaManager->actualizarEmpresa($data);

    echo json_encode($resultado);
    exit();
}

if ($_SERVER['REQUEST_URI'] == '/index.php/changePassword' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        http_response_code(400); 
        echo json_encode(['error' => 'Error en los datos JSON']);
        exit();
    }

    $resultado = $empresaManager->cambiarContrasena($data);

    echo json_encode($resultado);
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

    $resultado = $empresaManager->generateToken($data);

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

// Manejar solicitud para obtener cupones
if (strpos($_SERVER['REQUEST_URI'], '/index.php/getCoupon') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $usuarioEmpresa = $_GET['usuarioEmpresa'] ?? null;
    $resultado = $couponService->getCoupon($usuarioEmpresa);
    echo json_encode($resultado);
    exit();
}
    

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