<?php
require_once __DIR__ . '/../conexion.php';

class UsuarioDAO {

    private $db;

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

    
    // INSERTAR USUARIO
    
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

    
    // OBTENER POR CORREO

    public function obtenerPorCorreo($correo) {

        $sql = "SELECT * FROM usuarioS_APP WHERE correo = :correo LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':correo' => $correo]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    // LOGIN GENERAL 

    public function login($correo, $contrasena) {

        $usuario = $this->obtenerPorCorreo($correo);

        if (!$usuario) return false;

        if (password_verify($contrasena, $usuario['contrasena'])) {
            return $usuario;
        }

        return false;
    }
}
?>
