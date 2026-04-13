<?php
require_once __DIR__ .  '/Observador.php';

class Sujeto {
    private $observadores = [];

    public function agregarObservador(Observador $observador) {
        $this->observadores[] = $observador;
    }

    public function notificar($mensaje) {
        foreach ($this->observadores as $obs) {
            $obs->actualizar($mensaje);
        }
    }
}
?>
