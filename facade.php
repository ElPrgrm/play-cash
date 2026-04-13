<?php
require_once __DIR__ .  '/UsuarioFactory.php';
require_once __DIR__ .  '/MisionFactory.php';

class SistemaFacade {

    public static function registrarUsuario($correo, $nombre, $contrasena) {
        return UsuarioFactory::crearUsuario($correo, $nombre, $contrasena);
    }

    public static function iniciarSesion($correo, $contrasena) {
        return UsuarioFactory::iniciarSesion($correo, $contrasena);
    }

    public static function obtenerMisiones() {
        return MisionFactory::obtenerMisiones();
    }

}
?>
