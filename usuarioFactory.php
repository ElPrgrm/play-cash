<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/usuario.php';
require_once __DIR__ . '/dao/UsuarioDAO.php';

class UsuarioFactory {

    public static function crearUsuario($correo, $nombreUsuario, $contrasena, $rol = 'usuario') {

        $usuario = new Usuario($correo, $nombreUsuario, $contrasena, $rol);

        $dao = new UsuarioDAO();

        // Encriptación
        $hash = password_hash($usuario->contrasena, PASSWORD_DEFAULT);

        // Guardar con DAO
        $dao->insertarUsuario($usuario->correo, $usuario->nombreUsuario, $hash, $usuario->rol);

        return $usuario;
    }


    public static function iniciarSesion($correo, $contrasena) {

        $dao = new UsuarioDAO();
        $usuario = $dao->obtenerPorCorreo($correo);

        if (!$usuario) {
            return ['status' => false, 'mensaje' => 'Correo no registrado.'];
        }

        // Verificar contraseña
        if (password_verify($contrasena, $usuario['contrasena'])) {
            return ['status' => true, 'usuario' => $usuario];
        }

        return ['status' => false, 'mensaje' => 'Contraseña incorrecta.'];
    }


    public static function esAdmin($correo) {

        $dao = new UsuarioDAO();
        $usuario = $dao->obtenerPorCorreo($correo);

        return ($usuario && $usuario['rol'] === 'admin');
    }
}
?>
