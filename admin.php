<?php
session_start();
require_once __DIR__ . '/mediator.php';

//llamada al mediator
$data = SistemaMediator::manejarAccion('admin_cargar', []);

// Variables vista
$juegos = $data['juegos'];
$misiones = $data['misiones'];
$editMision = $data['editMision'];
$editJuego = $data['editJuego'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light p-4">

<div class="container">

    <h2 class="text-center mb-4"> PANEL ADMINISTRADOR </h2>

    <!-- ========================== FORMULARIO MISIÓN ========================== -->
    <div class="card mb-4">
      <div class="card-header bg-primary text-white">
        <?= $editMision ? "Modificar Misión #{$editMision['id_mision']}" : "Agregar Nueva Misión" ?>
      </div>
      <div class="card-body">

        <form action="funciones.php" method="POST">
          <input type="hidden" name="accion" value="<?= $editMision ? 'modificar_mision' : 'agregar_mision' ?>">
          <?php if ($editMision): ?>
            <input type="hidden" name="id_mision" value="<?= $editMision['id_mision'] ?>">
          <?php endif; ?>

          <div class="mb-3">
            <label>Juego:</label>
            <select name="id_juego" class="form-select" required>
              <option value="">Seleccione un juego</option>
              <?php foreach ($juegos as $j): ?>
                <option value="<?= $j['id_juego'] ?>"
                  <?= $editMision && $editMision['id_juego'] == $j['id_juego'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($j['nombreJuego']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label>Nombre de la Misión</label>
            <input type="text" class="form-control" name="nombre"
              value="<?= $editMision['nombreMision'] ?? '' ?>" required>
          </div>

          <div class="mb-3">
            <label>Descripción</label>
            <textarea class="form-control" name="descripcion" rows="3" required><?= $editMision['descripcion'] ?? '' ?></textarea>
          </div>

          <div class="mb-3">
            <label>Recompensa</label>
            <input type="number" class="form-control" name="recompensa"
              value="<?= $editMision['recompensa'] ?? '' ?>" required>
          </div>

          <button class="btn btn-success w-100">
            <?= $editMision ? "Guardar Cambios" : "Agregar Misión" ?>
          </button>
        </form>

      </div>
    </div>


    <!-- ========================== TABLA DE MISIONES ========================== -->
    <div class="card mb-5">
      <div class="card-header bg-dark text-white">Misiones Registradas</div>
      <div class="card-body">

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Juego</th>
              <th>Misión</th>
              <th>Descripción</th>
              <th>Recompensa</th>
              <th>Acciones</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($misiones as $m): ?>
              <tr>
                <td><?= $m['id_mision'] ?></td>
                <td><?= htmlspecialchars($m['nombreJuego']) ?></td>
                <td><?= htmlspecialchars($m['nombreMision']) ?></td>
                <td><?= htmlspecialchars($m['descripcion']) ?></td>
                <td><?= $m['recompensa'] ?></td>

                <td>
                  <a href="admin.php?edit_mision=<?= $m['id_mision'] ?>" class="btn btn-warning btn-sm">Modificar</a>
                  <form action="funciones.php" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar misión?');">
                <input type="hidden" name="accion" value="eliminar_mision">
                  <input type="hidden" name="id_mision" value="<?= $m['id_mision'] ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                </form>

                </td>

              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      </div>
    </div>


    <!-- ========================== FORMULARIO JUEGOS ========================== -->
    <div class="card mb-4">
      <div class="card-header bg-success text-white">
        <?= $editJuego ? "Modificar Juego #{$editJuego['id_juego']}" : "Agregar Nuevo Juego" ?>
      </div>

      <div class="card-body">
        <form action="funciones.php" method="POST">
          <input type="hidden" name="accion" value="<?= $editJuego ? 'modificar_juego' : 'agregar_juego' ?>">

          <?php if ($editJuego): ?>
            <input type="hidden" name="id_juego" value="<?= $editJuego['id_juego'] ?>">
          <?php endif; ?>

          <div class="mb-3">
            <label>Nombre del Juego</label>
            <input type="text" class="form-control" name="nombreJuego"
              value="<?= $editJuego['nombreJuego'] ?? '' ?>" required>
          </div>

          <button class="btn btn-primary w-100">
            <?= $editJuego ? "Guardar Cambios" : "Agregar Juego" ?>
          </button>
        </form>
      </div>
    </div>


    <!-- ========================== TABLA DE JUEGOS ========================== -->
    <div class="card">
      <div class="card-header bg-dark text-white">Juegos Registrados</div>

      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Juego</th>
              <th>Acciones</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($juegos as $j): ?>
              <tr>
                <td><?= $j['id_juego'] ?></td>
                <td><?= htmlspecialchars($j['nombreJuego']) ?></td>

                <td>
                  <a href="admin.php?edit_juego=<?= $j['id_juego'] ?>" class="btn btn-warning btn-sm">Modificar</a>
                  <form action="funciones.php" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar juego?');">
                <input type="hidden" name="accion" value="eliminar_juego">
                  <input type="hidden" name="id_juego" value="<?= $j['id_juego'] ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                </td>

              </tr>
            <?php endforeach; ?>
          </tbody>

        </table>
      </div>
    </div>

</div>
<br>
        <div class="text-end mb-3">
    <a href="index.php" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
</div>
</body>
</html>
