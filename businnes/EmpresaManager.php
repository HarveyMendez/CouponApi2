<?php
include_once 'data/Database.php';

class EmpresaManager {
    public function insertEmpresa($data) {
        // Validación y sanitización de los datos
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

        // Aquí deberías sanitizar y validar tus datos antes de usarlos en la consulta SQL

        $query = "INSERT INTO Empresa (nombre_empresa, nombre_usuario, direccion_fisica, cedula, fecha_creacion, correo_electronico, telefono, contrasena, ubicacion, estado)
                  VALUES('$nombre_empresa', '$nombre_usuario', '$direccion_fisica', '$cedula', '$fecha_creacion', '$correo_electronico', '$telefono', 'sincontraseña', '$ubicacion', 'true')";

        $resultado = metodoPost($query);
        return $resultado;
    }

    

}
?>