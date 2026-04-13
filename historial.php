<?php
require_once __DIR__ . '/MisionFactory.php';

$misionesCobradas = MisionFactory::obtenerMisionesCobradas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de misiones cobradas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4 bg-light">

<h1 class="mb-4 text-center">Historial de Misiones Cobradas</h1>

<div class="text-end mb-3">
    <a href="pantalla principal.php" class="btn btn-primary">Volver</a>
</div>

<div class="container">
    <?php if (empty($misionesCobradas)): ?>
        <div class="alert alert-info text-center">No has cobrado ninguna misión todavía.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped shadow">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Juego</th>
                    <th>Recompensa</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($misionesCobradas as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['nombreMision']) ?></td>
                        <td><?= htmlspecialchars($m['descripcion']) ?></td>
                        <td><?= htmlspecialchars($m['nombreJuego']) ?></td>
                        <td>$<?= number_format($m['recompensa'], 2) ?></td>
                        <td class="text-success fw-bold"><?= $m['estado'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
