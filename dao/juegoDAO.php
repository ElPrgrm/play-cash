<?php
require_once __DIR__ . '/../conexion.php';

class JuegoDAO {

    private $db;

    public function __construct() {
        if (class_exists('Database')) {
            $this->db = Database::getInstance()->getConnection();
        } else {
            global $db;
            $this->db = $db;
        }
    }

    // Listar juegos
    public function listar() {
        $stmt = $this->db->query("SELECT id_juego, nombreJuego FROM videojuegos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un juego por ID
    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM videojuegos WHERE id_juego = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear juego
    public function crear($nombre) {
        $stmt = $this->db->prepare("INSERT INTO videojuegos (nombreJuego) VALUES (:n)");
        return $stmt->execute([':n' => $nombre]);
    }

    // Modificar juego
    public function modificar($id, $nombre) {
        $stmt = $this->db->prepare("UPDATE videojuegos SET nombreJuego = :n WHERE id_juego = :id");
        return $stmt->execute([':n' => $nombre, ':id' => $id]);
    }

    // Eliminar juego
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM videojuegos WHERE id_juego = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>
