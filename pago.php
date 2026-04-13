<?php
require_once __DIR__ . '/helpers.php';
require_login();
require_once __DIR__ . '/misionFactory.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'confirmar_pago') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        set_flash_message('danger', 'La sesión del formulario expiró.');
        redirect('pantalla principal.php');
    }

    $id = filter_var($_POST['id_mision'] ?? null, FILTER_VALIDATE_INT);
    if (!$id) {
        set_flash_message('danger', 'Misión inválida.');
        redirect('pantalla principal.php');
    }

    MisionFactory::cobrarMision($id);
    set_flash_message('success', 'Pago registrado correctamente.');
    redirect('historial.php');
}

$id = filter_var($_POST['id_mision'] ?? $_GET['id_mision'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    set_flash_message('danger', 'No se recibió el ID de misión.');
    redirect('pantalla principal.php');
}

$mision = MisionFactory::obtenerMisionPorId($id);
if (!$mision) {
    set_flash_message('danger', 'Misión no encontrada.');
    redirect('pantalla principal.php');
}

$nombre = $mision['nombreMision'];
$recompensa = $mision['recompensa'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pago de Misión</title>
<link rel="stylesheet" href="pago.css">
</head>
<body>
<div class="checkout-box">
    <img class="card-icon" src="https://cdn-icons-png.flaticon.com/512/217/217425.png" alt="Card">
    <h2><?= htmlspecialchars($nombre) ?></h2>
    <p class="price">$<?= number_format((float) $recompensa, 2) ?> MXN</p>
    <form action="pago.php" method="post">
        <?= csrf_input() ?>
        <input type="hidden" name="accion" value="confirmar_pago">
        <input type="hidden" name="id_mision" value="<?= (int) $id ?>">
        <div class="input">
            <label>Número de tarjeta</label>
            <input type="text" placeholder="4242 4242 4242 4242" maxlength="19" required>
        </div>
        <div class="input">
            <label>Fecha de expiración</label>
            <input type="text" placeholder="MM / YY" maxlength="7" required>
        </div>
        <div class="input">
            <label>CVC</label>
            <input type="text" placeholder="123" maxlength="4" required>
        </div>
        <button class="btn" type="submit">Pagar Ahora</button>
    </form>
</div>
</body>
</html>
