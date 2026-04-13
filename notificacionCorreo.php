<?php
require_once __DIR__ . '/observador.php';
require_once __DIR__ . '/enviarCorreo.php';

class NotificacionCorreo implements Observador {
    private $destinatario;

    public function __construct($destinatario) {
        $this->destinatario = $destinatario;
    }

    public function actualizar($mensaje) {
        $asunto = " Nueva Mision Creada";
        enviarCorreo($this->destinatario, $asunto, $mensaje);
    }
}
?>
