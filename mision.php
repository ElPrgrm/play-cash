<?php
class Mision {
    public $id;
    public $nombre;
    public $descripcion;
    public $recompensa;
    public $id_juego;

    public function __construct($nombre, $descripcion, $recompensa, $id_juego = null) {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->recompensa = $recompensa;
        $this->id_juego = $id_juego;
    }
}
?>
