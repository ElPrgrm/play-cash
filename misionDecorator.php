<?php
require_once __DIR__ . '/mision.php';
require_once __DIR__ . '/conexion.php';

class MisionDecorator {
    protected $mision;

    public function __construct($mision) {
        $this->mision = $mision;
    }

    // Cambia el  color de la fila según si se acepto o rechazp
    public function getColor() {
        return match ($this->mision['estado'] ?? 'disponible') {
            'aceptada' => 'table-success',
            'rechazada' => 'table-danger',
            default => '',
        };
    }

    // Actualiza el estado en la base de datos
    public static function actualizarEstado($id_mision, $nuevoEstado) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE misiones SET estado = :estado WHERE id_mision = :id");
        $stmt->execute([':estado' => $nuevoEstado, ':id' => $id_mision]);
    }
}
?>
