<?php
require_once __DIR__ .  '/Mediator.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    SistemaMediator::manejarAccion($accion, $_POST);
} else {
    echo "<script>alert('Método no permitido.'); window.history.back();</script>";
}
?>
