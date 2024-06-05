<?php
require '../data/Database.php';

class EmpresaManager {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function insertarEmpresa($nombre_empresa, $nombre_usuario, $direccion_fisica, $cedula, $fecha_creacion, $correo_electronico, $telefono, $contrasena, $ubicacion, $estado) {
        return $this->db->insertarEmpresa($nombre_empresa, $nombre_usuario, $direccion_fisica, $cedula, $fecha_creacion, $correo_electronico, $telefono, $contrasena, $ubicacion, $estado);
    }
}
?>