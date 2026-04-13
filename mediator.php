<?php
require_once __DIR__ . '/helpers.php';
ensure_session_started();

require_once __DIR__ . '/usuarioFactory.php';
require_once __DIR__ . '/misionDecorator.php';
require_once __DIR__ . '/misionFactory.php';
require_once __DIR__ . '/dao/UsuarioDAO.php';
require_once __DIR__ . '/dao/MisionDAO.php';
require_once __DIR__ . '/dao/juegoDAO.php';

class SistemaMediator {
    private static function validateRole(?string $rol): string {
        return $rol === 'admin' ? 'admin' : 'usuario';
    }

    private static function validatePositiveAmount($value): float {
        if (!is_numeric($value)) {
            throw new InvalidArgumentException('La recompensa debe ser numérica.');
        }
        $amount = (float) $value;
        if ($amount < 0) {
            throw new InvalidArgumentException('La recompensa no puede ser negativa.');
        }
        return $amount;
    }

    public static function manejarAccion($accion, $datos) {
        $usuarioDAO = new UsuarioDAO();
        $misionDAO  = new MisionDAO();
        $juegoDAO   = new JuegoDAO();

        switch ($accion) {
            case 'registro':
                try {
                    $correo = sanitize_string($datos['Correo'] ?? '');
                    $nombreUsuario = sanitize_string($datos['nombreUsuario'] ?? '');
                    $contrasena = (string) ($datos['contrasena'] ?? '');

                    UsuarioFactory::crearUsuario($correo, $nombreUsuario, $contrasena, 'usuario');
                    set_flash_message('success', 'Registro completado. Ya puedes iniciar sesión.');
                    redirect('index.php');
                } catch (Throwable $e) {
                    set_flash_message('danger', $e->getMessage());
                    redirect('registrar.php');
                }
                break;

            case 'login':
                $correo = sanitize_string($datos['Correo'] ?? '');
                $contrasena = (string) ($datos['contrasena'] ?? '');

                if ($correo === '' || $contrasena === '') {
                    set_flash_message('warning', 'Por favor llena todos los campos.');
                    redirect('index.php');
                }

                $resultado = UsuarioFactory::iniciarSesion($correo, $contrasena);
                if (!$resultado['status']) {
                    set_flash_message('danger', $resultado['mensaje']);
                    redirect('index.php');
                }

                session_regenerate_id(true);
                $_SESSION['usuario'] = $resultado['usuario'];
                set_flash_message('success', 'Sesión iniciada correctamente.');
                redirect($_SESSION['usuario']['rol'] === 'admin' ? 'admin.php' : 'pantalla principal.php');
                break;

            case 'logout':
                $_SESSION = [];
                if (ini_get('session.use_cookies')) {
                    $params = session_get_cookie_params();
                    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
                }
                session_destroy();
                session_start();
                set_flash_message('success', 'Sesión cerrada.');
                redirect('index.php');
                break;

            case 'mision_accion':
                require_login();
                $id = filter_var($datos['id_mision'] ?? null, FILTER_VALIDATE_INT);
                $tipo = $datos['tipo'] ?? '';

                if (!$id || !in_array($tipo, ['aceptar', 'rechazar'], true)) {
                    set_flash_message('danger', 'Datos de misión inválidos.');
                    redirect('pantalla principal.php');
                }

                $nuevoEstado = ($tipo === 'aceptar') ? 'aceptada' : 'rechazada';
                MisionDecorator::actualizarEstado($id, $nuevoEstado);
                set_flash_message('success', $tipo === 'aceptar' ? 'Has aceptado la misión.' : 'Has rechazado la misión.');
                redirect('pantalla principal.php');
                break;

            case 'agregar_mision':
                require_admin();
                try {
                    $nombre = sanitize_string($datos['nombre'] ?? '');
                    $descripcion = sanitize_string($datos['descripcion'] ?? '');
                    $idJuego = filter_var($datos['id_juego'] ?? null, FILTER_VALIDATE_INT);
                    $recompensa = self::validatePositiveAmount($datos['recompensa'] ?? null);

                    if ($nombre === '' || $descripcion === '' || !$idJuego) {
                        throw new InvalidArgumentException('Todos los campos son obligatorios.');
                    }

                    MisionFactory::crearMision($nombre, $descripcion, $recompensa, $idJuego);
                    set_flash_message('success', 'Misión agregada.');
                } catch (Throwable $e) {
                    set_flash_message('danger', $e->getMessage());
                }
                redirect('admin.php');
                break;

            case 'agregar_usuario_admin':
                require_admin();
                try {
                    $correo = sanitize_string($datos['Correo'] ?? '');
                    $nombreUsuario = sanitize_string($datos['nombreUsuario'] ?? '');
                    $contrasena = (string) ($datos['contrasena'] ?? '');
                    $rol = self::validateRole($datos['rol'] ?? 'usuario');

                    UsuarioFactory::crearUsuario($correo, $nombreUsuario, $contrasena, $rol);
                    set_flash_message('success', 'Usuario creado correctamente.');
                } catch (Throwable $e) {
                    set_flash_message('danger', $e->getMessage());
                }
                redirect('admin.php');
                break;

            case 'actualizar_rol_usuario':
                require_admin();
                $idUsuario = filter_var($datos['id_usuario'] ?? null, FILTER_VALIDATE_INT);
                $rol = self::validateRole($datos['rol'] ?? 'usuario');

                if (!$idUsuario) {
                    set_flash_message('danger', 'Usuario inválido.');
                    redirect('admin.php');
                }

                if (!empty($_SESSION['usuario']['id_usuario']) && (int) $_SESSION['usuario']['id_usuario'] === $idUsuario) {
                    $_SESSION['usuario']['rol'] = $rol;
                }

                $usuarioDAO->actualizarRol($idUsuario, $rol);
                set_flash_message('success', 'Rol actualizado.');
                redirect('admin.php');
                break;

            case 'admin_cargar':
                require_admin();
                $juegos = $juegoDAO->listar();
                $misiones = $misionDAO->listarMisiones();
                $usuarios = $usuarioDAO->listarUsuarios();

                $editMision = null;
                if (isset($_GET['edit_mision'])) {
                    $idEditMision = filter_var($_GET['edit_mision'], FILTER_VALIDATE_INT);
                    if ($idEditMision) {
                        $editMision = $misionDAO->obtenerPorId($idEditMision);
                    }
                }

                $editJuego = null;
                if (isset($_GET['edit_juego'])) {
                    $idEditJuego = filter_var($_GET['edit_juego'], FILTER_VALIDATE_INT);
                    if ($idEditJuego) {
                        $editJuego = $juegoDAO->obtenerPorId($idEditJuego);
                    }
                }

                return [
                    'juegos' => $juegos,
                    'misiones' => $misiones,
                    'usuarios' => $usuarios,
                    'editMision' => $editMision,
                    'editJuego' => $editJuego,
                ];

            case 'modificar_mision':
                require_admin();
                try {
                    $idMision = filter_var($datos['id_mision'] ?? null, FILTER_VALIDATE_INT);
                    $idJuego = filter_var($datos['id_juego'] ?? null, FILTER_VALIDATE_INT);
                    $nombre = sanitize_string($datos['nombre'] ?? '');
                    $descripcion = sanitize_string($datos['descripcion'] ?? '');
                    $recompensa = self::validatePositiveAmount($datos['recompensa'] ?? null);

                    if (!$idMision || !$idJuego || $nombre === '' || $descripcion === '') {
                        throw new InvalidArgumentException('Faltan datos válidos.');
                    }

                    $misionDAO->modificar($idMision, $nombre, $descripcion, $recompensa, $idJuego);
                    set_flash_message('success', 'Misión modificada.');
                } catch (Throwable $e) {
                    set_flash_message('danger', $e->getMessage());
                }
                redirect('admin.php');
                break;

            case 'agregar_juego':
                require_admin();
                $nombreJuego = sanitize_string($datos['nombreJuego'] ?? '');
                if ($nombreJuego === '') {
                    set_flash_message('danger', 'El nombre del juego es obligatorio.');
                } else {
                    $juegoDAO->crear($nombreJuego);
                    set_flash_message('success', 'Juego agregado.');
                }
                redirect('admin.php');
                break;

            case 'modificar_juego':
                require_admin();
                $idJuego = filter_var($datos['id_juego'] ?? null, FILTER_VALIDATE_INT);
                $nombreJuego = sanitize_string($datos['nombreJuego'] ?? '');
                if (!$idJuego || $nombreJuego === '') {
                    set_flash_message('danger', 'Datos del juego inválidos.');
                } else {
                    $juegoDAO->modificar($idJuego, $nombreJuego);
                    set_flash_message('success', 'Juego modificado.');
                }
                redirect('admin.php');
                break;

            case 'eliminar_juego':
                require_admin();
                $idJuego = filter_var($datos['id_juego'] ?? null, FILTER_VALIDATE_INT);
                if (!$idJuego) {
                    set_flash_message('danger', 'Juego inválido.');
                } else {
                    $juegoDAO->eliminar($idJuego);
                    set_flash_message('success', 'Juego eliminado.');
                }
                redirect('admin.php');
                break;

            case 'eliminar_mision':
                require_admin();
                $idMision = filter_var($datos['id_mision'] ?? null, FILTER_VALIDATE_INT);
                if (!$idMision) {
                    set_flash_message('danger', 'Misión inválida.');
                } else {
                    $misionDAO->eliminar($idMision);
                    set_flash_message('success', 'Misión eliminada.');
                }
                redirect('admin.php');
                break;

            default:
                set_flash_message('danger', 'Acción no válida.');
                redirect('index.php');
        }
    }
}
?>
