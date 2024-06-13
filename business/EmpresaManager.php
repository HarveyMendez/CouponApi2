<?php
include_once 'data/Database.php';

class EmpresaManager {
    public function insertEmpresa($data) {

        if ($data === null) {
            return ['error' => 'Error en los datos JSON'];
        }

        $nombre_empresa = $data['nombre_empresa'];
        $nombre_usuario = $data['nombre_usuario'];
        $direccion_fisica = $data['direccion_fisica'];
        $cedula = $data['cedula'];
        $fecha_creacion = date('Y-m-d');
        $correo_electronico = $data['correo_electronico'];
        $telefono = $data['telefono'];
        $ubicacion = $data['ubicacion'];

        $query = "INSERT INTO Empresa (nombre_empresa, nombre_usuario, direccion_fisica, cedula, fecha_creacion, correo_electronico, telefono, contrasena, ubicacion, estado)
                  VALUES('$nombre_empresa', '$nombre_usuario', '$direccion_fisica', '$cedula', '$fecha_creacion', '$correo_electronico', '$telefono', 'sincontraseña', '$ubicacion', 'true')";

        $resultado = metodoPost($query);
        return $resultado;
    }

    public function getBusiness($usuarioEmpresa = null) {
        if ($usuarioEmpresa) {
            $query = "SELECT * FROM Empresa WHERE nombre_usuario='$usuarioEmpresa'";
        } else {
            $query = "SELECT * FROM Empresa";
        }
        $resultado = metodoGet($query);
        return $resultado->fetchAll();
    }

    public function cambiarEstadoEmpresa($data) {
        $usuarioEmpresa = $data['nombre_usuario'];

        $query = "UPDATE Empresa 
                    SET estado = NOT estado 
                    WHERE nombre_usuario = '$usuarioEmpresa'";

        return metodoPut($query);
    }

    public function actualizarEmpresa($data) {
        $nombre_usuario = $data['nombre_usuario'];
        $nombre_empresa = $data['nombre_empresa'];
        $direccion_fisica = $data['direccion_fisica'];
        $cedula = $data['cedula'];
        $correo_electronico = $data['correo_electronico'];
        $telefono = $data['telefono'];
        $ubicacion = $data['ubicacion'];

        $query = "UPDATE Empresa 
                    SET nombre_empresa = '$nombre_empresa', 
                        direccion_fisica = '$direccion_fisica', 
                        cedula = '$cedula', 
                        correo_electronico = '$correo_electronico', 
                        telefono = '$telefono', 
                        ubicacion = '$ubicacion'
                    WHERE nombre_usuario = '$nombre_usuario'";

        return metodoPut($query);
    }

    public function cambiarContrasena($data) {
        $usernameBusiness = $data['username']; 
        $newPasswordBusiness = $data['password'];

        $query1 = "UPDATE Empresa 
                    SET contrasena = '$newPasswordBusiness'
                    WHERE nombre_usuario = '$usernameBusiness'";

        $query2 = "UPDATE Claves 
                    SET userEmpresa = NULL 
                    WHERE userEmpresa = '$usernameBusiness'";

        $resultado1 = metodoPut($query1);
        $resultado2 = metodoPut($query2);

        if ($resultado1 === false || $resultado2 === false) {
            return ['message' => 'Error al actualizar la contraseña o desvincular el usuario'];
        } else {
            return ['message' => 'Contraseña actualizada y usuario desvinculado correctamente'];
        }
    }

    public function generateToken($data) {
        $nombre_usuario = $data['nombre_usuario']; 


    $query0 = "SELECT claveTemp from Claves 
                WHERE userEmpresa = '$nombre_usuario'";

    $resultado0 = metodoGet($query0);

    if ($resultado0 && $resultado0->rowCount() > 0) {
        return $resultado0->fetchAll();
    }

    
    $query1 = "SELECT id FROM Claves WHERE userEmpresa IS NULL ORDER BY RAND() LIMIT 1;";
    $resultado1 = metodoGet($query1);
    if (!$resultado1) {
        return ['error' => 'Error al obtener el id aleatorio'];
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
    $array = $resultado3->fetchAll(PDO::FETCH_ASSOC);
    $token = $array[0]['claveTemp'];

    $query4 = "UPDATE Empresa 
                    SET contrasena = '$token'
                    WHERE nombre_usuario = '$nombre_usuario'";

    $resultado4 = metodoPut($query4);  

    
    
    if ($array) {
        return $array;
    } else {
        return ['error' => 'Error al asignar el nombre de usuario al token'];
    }
    }

    public function businessLogin($data) {

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
            return ['nombre_usuario' => '', 'contrasenna' => ''];
        } else {
            return $resultado->fetch(PDO::FETCH_ASSOC);
        }
    }

}
