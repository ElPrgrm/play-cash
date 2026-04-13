<?php
require_once __DIR__ . '/MisionFactory.php';

// Validar ID
if (!isset($_GET['id_mision'])) {
    die("No se recibió el ID de misión.");
}

$id = $_GET['id_mision'];

// 🔥 Marcar como COBRADA en la BD
MisionFactory::cobrarMision($id);

// Obtener datos desde la BD
$mision = MisionFactory::obtenerMisionPorId($id);

if (!$mision) {
    die("Misión no encontrada.");
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

    <p class="price">$<?= number_format($recompensa, 2) ?> MXN</p>

    <div class="input">
        <label>Número de tarjeta</label>
        <input type="text" placeholder="4242 4242 4242 4242">
    </div>

    <div class="input">
        <label>Fecha de expiración</label>
        <input type="text" placeholder="MM / YY">
    </div>

    <div class="input">
        <label>CVC</label>
        <input type="text" placeholder="123">
    </div>

    <button class="btn" onclick="window.location.href='pantalla principal.php'">
    Pagar Ahora
</button>


</div>

</body>
</html>
