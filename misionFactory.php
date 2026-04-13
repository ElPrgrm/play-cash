<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/mision.php';
require_once __DIR__ . '/Sujeto.php';
require_once __DIR__ . '/notificacionCorreo.php';
require_once __DIR__ . '/dao/MisionDAO.php';

class MisionFactory {

    public static function crearMision($nombre, $descripcion, $recompensa, $id_juego) {

        $dao = new MisionDAO();
        $dao->crearMision($nombre, $descripcion, $recompensa, $id_juego);

        // Notificación
        $sujeto = new Sujeto();
        $sujeto->agregarObservador(new NotificacionCorreo(['Usuario', 'jona.ramirez.guzman.1784@gmail.com']));
        $sujeto->notificar("Se ha creado una nueva misión: <b>$nombre</b><br>Recompensa: $recompensa pesos.");

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


//Cobrar

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
