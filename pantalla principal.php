<?php
require_once __DIR__ . '/helpers.php';
require_login();
require_once __DIR__ . '/misionFactory.php';
require_once __DIR__ . '/misionDecorator.php';
$misiones = MisionFactory::obtenerMisiones();
$flash = get_flash_message();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Misiones disponibles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="p-4 bg-light">
<div class="d-flex justify-content-between mb-3">
    <a href="historial.php" class="btn btn-secondary"><i class="bi bi-clock-history"></i> Historial</a>
</div>
<h1 class="mb-4 text-center">Misiones Disponibles</h1>
<div class="container">
    <?php if ($flash): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>" role="alert">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>
    <?php if (empty($misiones)): ?>
        <div class="alert alert-warning text-center">No hay misiones disponibles por ahora.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped shadow">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Juego</th>
                    <th>Recompensa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($misiones as $m): $decorador = new MisionDecorator($m); $color = $decorador->getColor(); ?>
                <tr class="<?= htmlspecialchars($color) ?>">
                    <td><?= htmlspecialchars($m['nombreMision']) ?></td>
                    <td><?= htmlspecialchars($m['descripcion']) ?></td>
                    <td><?= htmlspecialchars($m['nombreJuego']) ?></td>
                    <td>$<?= number_format((float) $m['recompensa'], 2) ?></td>
                    <td class="text-center">
                        <form action="funciones.php" method="post" class="d-inline">
                            <?= csrf_input() ?>
                            <input type="hidden" name="accion" value="mision_accion">
                            <input type="hidden" name="id_mision" value="<?= (int) $m['id_mision'] ?>">
                            <input type="hidden" name="tipo" value="aceptar">
                            <button class="btn btn-success btn-sm">Aceptar</button>
                        </form>
                        <form action="funciones.php" method="post" class="d-inline">
                            <?= csrf_input() ?>
                            <input type="hidden" name="accion" value="mision_accion">
                            <input type="hidden" name="id_mision" value="<?= (int) $m['id_mision'] ?>">
                            <input type="hidden" name="tipo" value="rechazar">
                            <button class="btn btn-danger btn-sm">Rechazar</button>
                        </form>
                        <?php if ($m['estado'] === 'aceptada'): ?>
                            <form action="pago.php" method="post" class="d-inline">
                                <?= csrf_input() ?>
                                <input type="hidden" name="id_mision" value="<?= (int) $m['id_mision'] ?>">
                                <button class="btn btn-primary btn-sm">Cobrar</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <div class="text-end mb-3">
        <form action="funciones.php" method="POST">
            <?= csrf_input() ?>
            <input type="hidden" name="accion" value="logout">
            <button class="btn btn-outline-danger btn-sm">Cerrar sesión</button>
        </form>
    </div>
</div>
</body>
</html>
