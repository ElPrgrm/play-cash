<?php
require_once __DIR__ . '/UsuarioFactory.php';
require_once __DIR__ . '/MisionDecorator.php';
require_once __DIR__ . '/MisionFactory.php';

require_once __DIR__ . '/dao/UsuarioDAO.php';
require_once __DIR__ . '/dao/MisionDAO.php';
require_once __DIR__ . '/dao/JuegoDAO.php';

class SistemaMediator {

    public static function manejarAccion($accion, $datos) {

        $usuarioDAO = new UsuarioDAO();
        $misionDAO  = new MisionDAO();
        $juegoDAO   = new JuegoDAO();

        switch ($accion) {

          
            // REGISTRO

            case 'registro':

                if (!empty($datos['Correo']) &&
                    !empty($datos['nombreUsuario']) &&
                    !empty($datos['contrasena'])) 
                {
                    try {
                        UsuarioFactory::crearUsuario(
                            $datos['Correo'],
                            $datos['nombreUsuario'],
                            $datos['contrasena']
                        );

                        header("Location: pantalla principal.php");
                        exit;

                    } catch (PDOException $e) {
                        echo "<h3>Error al registrar: {$e->getMessage()}</h3>";
                    }
                } else {
                    echo "<h3>⚠ Todos los campos son obligatorios.</h3>";
                }

                break;


         
            // LOGIN
         
            case 'login':

                if (!empty($datos['Correo']) && !empty($datos['contrasena'])) {

                    $resultado = UsuarioFactory::iniciarSesion(
                        $datos['Correo'],
                        $datos['contrasena']
                    );

                    if ($resultado['status']) {

                        $_SESSION['usuario'] = $resultado['usuario'];

                        if ($_SESSION['usuario']['rol'] === 'admin') {
                            header("Location: admin.php");
                        } else {
                            header("Location: pantalla principal.php");
                        }
                        exit;

                    } else {
                        echo "<script>alert('{$resultado['mensaje']}'); window.history.back();</script>";
                    }

                } else {
                    echo "<script>alert('Por favor llena todos los campos'); window.history.back();</script>";
                }

                break;


         
            // ACCIONES DE MISIÓN
    
            case 'mision_accion':

                $id   = $datos['id_mision'] ?? null;
                $tipo = $datos['tipo'] ?? '';

                if ($id && in_array($tipo, ['aceptar', 'rechazar'])) {

                    $nuevoEstado = ($tipo === 'aceptar') ? 'aceptada' : 'rechazada';
                    MisionDecorator::actualizarEstado($id, $nuevoEstado);

                    $mensaje = ($tipo === 'aceptar')
                        ? 'Has aceptado la misión'
                        : 'Has rechazado la misión';

                    echo "<script>alert('$mensaje'); window.location.href='pantalla principal.php';</script>";

                } else {
                    echo "<script>alert('Datos de misión inválidos'); window.history.back();</script>";
                }

                break;


            // AGREGAR MISIÓN 
         
            case 'agregar_mision':

                if (!empty($datos['nombre']) &&
                    !empty($datos['descripcion']) &&
                    !empty($datos['recompensa']) &&
                    !empty($datos['id_juego']))
                {

                    try {
                        MisionFactory::crearMision(
                            $datos['nombre'],
                            $datos['descripcion'],
                            $datos['recompensa'],
                            $datos['id_juego']
                        );

                        echo "<script>alert('Misión agregada'); window.location.href='admin.php';</script>";

                    } catch (PDOException $e) {
                        echo "<script>alert('Error: {$e->getMessage()}'); window.history.back();</script>";
                    }

                } else {
                    echo "<script>alert('Todos los campos son obligatorios'); window.history.back();</script>";
                }

                break;


         
            // ADMIN
           
            case 'admin_cargar':

                $juegos   = $juegoDAO->listar();
                $misiones = $misionDAO->listarMisiones();

                // EDITAR MISIÓN
                $editMision = null;
                if (isset($_GET['edit_mision'])) {
                    $editMision = $misionDAO->obtenerPorId($_GET['edit_mision']);
                }

                // EDITAR JUEGO
                $editJuego = null;
                if (isset($_GET['edit_juego'])) {
                    $editJuego = $juegoDAO->obtenerPorId($_GET['edit_juego']);
                }

                return [
                    'juegos'     => $juegos,
                    'misiones'   => $misiones,
                    'editMision' => $editMision,
                    'editJuego'  => $editJuego
                ];

                break;


            // MODIFICAR MISIÓN
           
            case 'modificar_mision':

                if (!empty($_POST['id_mision']) &&
                    !empty($_POST['nombre']) &&
                    !empty($_POST['descripcion']) &&
                    isset($_POST['recompensa']) &&
                    !empty($_POST['id_juego']))
                {
                    $misionDAO->modificar(
                        $_POST['id_mision'],
                        $_POST['nombre'],
                        $_POST['descripcion'],
                        $_POST['recompensa'],
                        $_POST['id_juego']
                    );

                    echo "<script>alert('Misión modificada'); window.location.href='admin.php';</script>";
                } 
                else {
                    echo "<script>alert('Faltan datos'); window.history.back();</script>";
                }

                break;


         
            // CRUD JUEGOS
          
            case 'agregar_juego':

                if (!empty($_POST['nombreJuego'])) {
                    $juegoDAO->crear($_POST['nombreJuego']);

                    echo "<script>alert('Juego agregado'); window.location.href='admin.php';</script>";
                }

                break;


            case 'modificar_juego':

                if (!empty($_POST['id_juego']) && !empty($_POST['nombreJuego'])) {
                    $juegoDAO->modificar($_POST['id_juego'], $_POST['nombreJuego']);

                    echo "<script>alert('Juego modificado'); window.location.href='admin.php';</script>";
                }

                break;


            case 'eliminar_juego':

                if (!empty($_POST['id_juego'])) {
                    $juegoDAO->eliminar($_POST['id_juego']);
                    echo "<script>alert('Juego eliminado'); window.location.href='admin.php';</script>";
                }

                break;


            // ELIMINAR MISIÓN
        
            case 'eliminar_mision':

                if (!empty($_POST['id_mision'])) {
                    $misionDAO->eliminar($_POST['id_mision']);
                    echo "<script>alert('Misión eliminada'); window.location.href='admin.php';</script>";
                }

                break;


           
            // DEFAULT
          
            default:
                echo "<script>alert('Acción no válida'); window.history.back();</script>";
        }
    }
}
?>
