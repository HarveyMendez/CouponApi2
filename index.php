<?php
include_once 'business/EmpresaManager.php';
include_once 'business/CouponManager.php';

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

        $resultado = $empresaManager->businessLogin($data);

        echo json_encode($resultado);

        exit();
}   

if (strpos($_SERVER['REQUEST_URI'], '/index.php/getUserCoupon') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = $_GET['id'] ?? null;
    $resultado = $couponManager->getUserCoupon($id);
    echo json_encode($resultado);
    exit();
}

if (strpos($_SERVER['REQUEST_URI'], '/index.php/getCategories') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $idCategoria = $_GET['id'] ?? null;
    $resultado = $couponManager->getCategories($idCategoria);
    echo json_encode($resultado);
    exit();
}

if (strpos($_SERVER['REQUEST_URI'], '/index.php/getBusiness') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $usuarioEmpresa = $_GET['usuarioEmpresa'] ?? null;
    $resultado = $empresaManager->getBusiness($usuarioEmpresa);
    echo json_encode($resultado);
    exit();
}

if (strpos($_SERVER['REQUEST_URI'], '/index.php/getCoupon') !== false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $usuarioEmpresa = $_GET['usuarioEmpresa'] ?? null;
    $resultado = $couponManager->getCoupon($usuarioEmpresa);
    echo json_encode($resultado);
    exit();
}






?>