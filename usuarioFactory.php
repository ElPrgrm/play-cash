<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/usuario.php';
require_once __DIR__ . '/dao/UsuarioDAO.php';

class UsuarioFactory {
    public static function crearUsuario($correo, $nombreUsuario, $contrasena, $rol = 'usuario') {
        $correo = trim((string) $correo);
        $nombreUsuario = trim((string) $nombreUsuario);
        $rol = ($rol === 'admin') ? 'admin' : 'usuario';

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Correo inválido.');
        }

        if ($nombreUsuario === '' || mb_strlen($nombreUsuario) > 100) {
            throw new InvalidArgumentException('Nombre de usuario inválido.');
        }

        if (mb_strlen($contrasena) < 8) {
            throw new InvalidArgumentException('La contraseña debe tener al menos 8 caracteres.');
        }

        $dao = new UsuarioDAO();
        if ($dao->obtenerPorCorreo($correo)) {
            throw new RuntimeException('El correo ya está registrado.');
        }

        $usuario = new Usuario($correo, $nombreUsuario, $contrasena, $rol);
        $hash = password_hash($usuario->contrasena, PASSWORD_DEFAULT);
        $dao->insertarUsuario($usuario->correo, $usuario->nombreUsuario, $hash, $usuario->rol);

        return $usuario;
    }

    public static function iniciarSesion($correo, $contrasena) {
        $dao = new UsuarioDAO();
        $usuario = $dao->obtenerPorCorreo(trim((string) $correo));

        if (!$usuario) {
            return ['status' => false, 'mensaje' => 'Correo no registrado.'];
        }

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
