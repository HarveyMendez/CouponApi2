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

}
?>