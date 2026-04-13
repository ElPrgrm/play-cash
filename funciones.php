<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/mediator.php';

ensure_session_started();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    set_flash_message('danger', 'Método no permitido.');
    redirect('index.php');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
    set_flash_message('danger', 'La sesión del formulario expiró. Intenta de nuevo.');
    redirect($_SERVER['HTTP_REFERER'] ?? 'index.php');
}

$accion = $_POST['accion'] ?? '';
SistemaMediator::manejarAccion($accion, $_POST);
?>
