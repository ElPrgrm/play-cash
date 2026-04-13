<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/mision.php';
require_once __DIR__ . '/sujeto.php';
require_once __DIR__ . '/notificacionCorreo.php';
require_once __DIR__ . '/dao/MisionDAO.php';

class MisionFactory {
    public static function crearMision($nombre, $descripcion, $recompensa, $id_juego) {
        $dao = new MisionDAO();
        $dao->crearMision($nombre, $descripcion, $recompensa, $id_juego);

        $correoAdmin = getenv('MISSION_NOTIFY_EMAIL') ?: '';
        if (filter_var($correoAdmin, FILTER_VALIDATE_EMAIL)) {
            try {
                $sujeto = new Sujeto();
                $sujeto->agregarObservador(new NotificacionCorreo(['Administrador', $correoAdmin]));
                $safeNombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
                $safeRecompensa = number_format((float) $recompensa, 2);
                $sujeto->notificar("Se ha creado una nueva misión: <b>{$safeNombre}</b><br>Recompensa: {$safeRecompensa} pesos.");
            } catch (Throwable $e) {
                // El correo es opcional: no romper el flujo principal.
            }
        }

        return new Mision($nombre, $descripcion, $recompensa, $id_juego);
    }

    public static function obtenerMisiones() {
        $dao = new MisionDAO();
        return $dao->listarMisiones();
    }

    public static function registrarAccion($id_mision, $tipo) {
        if ($tipo === 'aceptar') return "Has aceptado la misión correctamente";
        if ($tipo === 'rechazar') return "Has rechazado la misión";
        return "Acción no válida para la misión.";
    }

    public static function obtenerMisionPorId($id_mision) {
        $dao = new MisionDAO();
        return $dao->obtenerPorId($id_mision);
    }

    public static function cobrarMision($id_mision) {
        $dao = new MisionDAO();
        return $dao->marcarComoCobrada($id_mision);
    }

    public static function obtenerMisionesCobradas() {
        $dao = new MisionDAO();
        return $dao->obtenerCobradas();
    }
}
?>
