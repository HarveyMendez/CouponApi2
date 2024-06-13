<?php
include_once 'data/Database.php';

class CouponManager {
    public function getCoupon($usuarioEmpresa = null) {
        if ($usuarioEmpresa) {
            $query = "SELECT * FROM Cupones WHERE usuarioEmpresa='$usuarioEmpresa'";
        } else {
            $query = "SELECT * FROM Cupones";
        }
        $resultado = metodoGet($query);
        return $resultado->fetchAll();
    }

    public function getUserCoupon($id = null) {
        if ($id) {
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
                    WHERE c.estado=true AND c.id='$id'";
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
                    WHERE c.estado=true and c.cantidad > 0";
        }
        $resultado = metodoGet($query);
        return $resultado->fetchAll();
    }

    public function getCategories($idCategoria = null) {
        if ($idCategoria) {
            $query = "SELECT * FROM Categorias WHERE id='$idCategoria'";
        } else {
            $query = "SELECT * FROM Categorias";
        }
        $resultado = metodoGet($query);
        return $resultado->fetchAll();
    }

    public function agregarCupon($data) {
        // Validar y manipular los datos aquí
        // Ejemplo de cómo podrías hacerlo:

        $timestamp = date('YmdHis');
        $randomString = substr(md5(uniqid(mt_rand(), true)), 0, 6);
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
        $imagen = $data['image'];

        // Ejecutar la consulta para insertar el cupón en la base de datos
        $query = "INSERT INTO Cupones(usuarioEmpresa, fecha_creacion, fecha_inicio, fecha_vencimiento, nombre, precio, estado, categoria, cantidad, descuento, image, codigo) VALUES( '$nombre_usuario', '$fecha_creacion', '$fecha_inicio', '$fecha_vencimiento', '$nombre', '$precio', '$estado', '$categoria', '$cantidad', '$descuento', $imagen, '$codigo')";

        $resultado = metodoPost($query); // Llamar al método de la capa de acceso a datos

        return $resultado;
    }

    public function actualizarCupon($data) {
        // Validar y manipular los datos si es necesario
        // Ejemplo de actualización en la base de datos
        $nombre_usuario = $data['nombre_usuario'];
        $fecha_inicio = $data['fechaInicio'];
        $fecha_vencimiento = $data['fechaVencimiento'];
        $nombre = $data['nombre'];
        $precio = $data['precio'];
        $estado = $data['estado'];
        $categoria = $data['categoria'];
        $cantidad = $data['cantidad'];
        $descuento = $data['descuento'];

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
        return metodoPut($query);
    }

    public function cambiarEstadoCupon($data) {
        // Validar y manipular los datos si es necesario
        $usuarioEmpresa = $data['nombre_usuario'];
        $nombreCupon = $data['nombre'];

        $query = "UPDATE Cupones 
                    SET estado = NOT estado 
                    WHERE nombre = '$nombreCupon' and usuarioEmpresa = '$usuarioEmpresa'";

        return metodoPut($query);
    }

    public function comprarCupon($data) {
        // Validar y manipular los datos si es necesario
        $codigo = $data['codigo'];
        $cantidad = $data['cantidad'];

        $query = "UPDATE Cupones 
                    SET cantidad = cantidad - $cantidad 
                    WHERE codigo = '$codigo'";

        return metodoPut($query);
    }

}
