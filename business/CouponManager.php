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
                    WHERE c.estado=true and c.cantidad > 0 and e.estado = 1";
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

        $query = "INSERT INTO Cupones(usuarioEmpresa, fecha_creacion, fecha_inicio, fecha_vencimiento, nombre, precio, estado, categoria, cantidad, descuento, image, codigo) VALUES( '$nombre_usuario', '$fecha_creacion', '$fecha_inicio', '$fecha_vencimiento', '$nombre', '$precio', '$estado', '$categoria', '$cantidad', '$descuento', '$imagen', '$codigo')";

        $resultado = metodoPost($query);

        return $resultado;
    }

    public function actualizarCupon($data) {
        $nombre_usuario = $data['nombre_usuario'];
        $fecha_inicio = $data['fechaInicio'];
        $fecha_vencimiento = $data['fechaVencimiento'];
        $nombre = $data['nombre'];
        $precio = $data['precio'];
        $estado = $data['estado'];
        $categoria = $data['categoria'];
        $cantidad = $data['cantidad'];
        $descuento = $data['descuento'];
        $imagen = $data['image'];

        $query = "UPDATE Cupones 
                    SET fecha_inicio = '$fecha_inicio', 
                        fecha_vencimiento = '$fecha_vencimiento', 
                        nombre = '$nombre', 
                        precio = '$precio', 
                        estado = '$estado', 
                        categoria = '$categoria', 
                        cantidad = '$cantidad', 
                        descuento = '$descuento',
                        image = '$imagen'
                    WHERE usuarioEmpresa = '$nombre_usuario' 
                        AND nombre = '$nombre'";
        return metodoPut($query);
    }

    public function cambiarEstadoCupon($data) {
        $usuarioEmpresa = $data['nombre_usuario'];
        $nombreCupon = $data['nombre'];

        $query = "UPDATE Cupones 
                    SET estado = NOT estado 
                    WHERE nombre = '$nombreCupon' and usuarioEmpresa = '$usuarioEmpresa'";

        return metodoPut($query);
    }

    public function comprarCupon($data) {
        $codigo = $data['codigo'];
        $cantidad = $data['cantidad'];

        $query = "UPDATE Cupones 
                    SET cantidad = cantidad - $cantidad 
                    WHERE codigo = '$codigo'";

        return metodoPut($query);
    }

}
