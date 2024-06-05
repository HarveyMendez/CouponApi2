<?php
require '../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class Database {
    private $pdo;
    private $host;
    private $user;
    private $password;
    private $dbname;

    public function __construct() {
        $this->host = $_ENV['DB_HOST'];
        $this->user = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->dbname = $_ENV['DB_NAME'];

        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error! : No se pudo conectar a la base de datos $this->dbname<br/>Error! :" . $e->getMessage());
        }
    }

    public function insertarEmpresa($nombre_empresa, $nombre_usuario, $direccion_fisica, $cedula, $fecha_creacion, $correo_electronico, $telefono, $contrasena, $ubicacion, $estado) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO Empresa (nombre_empresa, nombre_usuario, direccion_fisica, cedula, fecha_creacion, correo_electronico, telefono, contrasena, ubicacion, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nombre_empresa, $nombre_usuario, $direccion_fisica, $cedula, $fecha_creacion, $correo_electronico, $telefono, $contrasena, $ubicacion, $estado]);
            return array("status" => "success", "message" => "Data inserted successfully");
        } catch (PDOException $e) {
            return array("status" => "error", "message" => "Data insertion failed: " . $e->getMessage());
        }
    }
}
?>