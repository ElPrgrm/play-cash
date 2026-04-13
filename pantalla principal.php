<?php
require_once __DIR__ .  '/MisionFactory.php';
require_once __DIR__ . '/MisionDecorator.php';
$misiones = MisionFactory::obtenerMisiones();
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

    <!-- Botón Historial -->
    <a href="historial.php" class="btn btn-secondary">
        <i class="bi bi-clock-history"></i> Historial
    </a>

    
</div>

    <h1 class="mb-4 text-center"> Misiones Disponibles</h1>

    <div class="container">
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
                <?php foreach ($misiones as $m): 
                    $decorador = new MisionDecorator($m);
                    $color = $decorador->getColor();
                ?>
                    <tr class="<?= $color ?>">
                        <td><?= htmlspecialchars($m['nombreMision']) ?></td>
                        <td><?= htmlspecialchars($m['descripcion']) ?></td>
                        <td><?= htmlspecialchars($m['nombreJuego']) ?></td>
                        <td>$<?= number_format($m['recompensa'], 2) ?></td>
                        <td class="text-center">
                            <form action="funciones.php" method="post" class="d-inline">
                                <input type="hidden" name="accion" value="mision_accion">
                                <input type="hidden" name="id_mision" value="<?= $m['id_mision'] ?>">
                                <input type="hidden" name="tipo" value="aceptar">
                                <button class="btn btn-success btn-sm">Aceptar</button>
                            </form>

                            <form action="funciones.php" method="post" class="d-inline">
                                <input type="hidden" name="accion" value="mision_accion">
                                <input type="hidden" name="id_mision" value="<?= $m['id_mision'] ?>">
                                <input type="hidden" name="tipo" value="rechazar">
                                <button class="btn btn-danger btn-sm">Rechazar</button>
                            </form>


                           <?php if ($m['estado'] === 'aceptada'): ?>

                      <form action="pago.php" method="get" class="d-inline">
                    <input type="hidden" name="id_mision" value="<?= $m['id_mision'] ?>">
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
    <a href="index.php" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
</div>

    </div>

</body>
</html>
