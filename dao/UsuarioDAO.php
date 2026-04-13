<?php
require_once __DIR__ . '/../conexion.php';

class UsuarioDAO {
    private PDO $db;

    public function __construct() {
        if (class_exists('Database')) {
            $this->db = Database::getInstance()->getConnection();
        } elseif (class_exists('BD_PDO')) {
            $this->db = BD_PDO::getInstance()->getConnection();
        } else {
            global $db;
            $this->db = $db;
        }
    }

    public function insertarUsuario($correo, $nombreUsuario, $contrasenaHash, $rol) {
        $sql = "INSERT INTO usuarioS_APP (correo, NombreUsuario, contrasena, rol)
                VALUES (:correo, :nombreUsuario, :contrasena, :rol)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':correo'        => $correo,
            ':nombreUsuario' => $nombreUsuario,
            ':contrasena'    => $contrasenaHash,
            ':rol'           => $rol
        ]);
    }

    public function obtenerPorCorreo($correo) {
        $sql = "SELECT * FROM usuarioS_APP WHERE correo = :correo LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':correo' => $correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id) {
        $sql = "SELECT * FROM usuarioS_APP WHERE id_usuario = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarUsuarios(): array {
        $sql = "SELECT id_usuario, correo, NombreUsuario, rol FROM usuarioS_APP ORDER BY id_usuario DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarRol(int $idUsuario, string $rol): bool {
        $sql = "UPDATE usuarioS_APP SET rol = :rol WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':rol' => $rol,
            ':id' => $idUsuario,
        ]);
    }

    public function login($correo, $contrasena) {
        $usuario = $this->obtenerPorCorreo($correo);
        if (!$usuario) {
            return false;
        }

        if (password_verify($contrasena, $usuario['contrasena'])) {
            return $usuario;
        }

        return false;
    }
}
?>
