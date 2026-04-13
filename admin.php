<?php
require_once __DIR__ . '/helpers.php';
require_admin();
require_once __DIR__ . '/mediator.php';

$data = SistemaMediator::manejarAccion('admin_cargar', []);
$juegos = $data['juegos'];
$misiones = $data['misiones'];
$usuarios = $data['usuarios'];
$editMision = $data['editMision'];
$editJuego = $data['editJuego'];
$flash = get_flash_message();
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
    <h2 class="text-center mb-4">PANEL ADMINISTRADOR</h2>
    <?php if ($flash): ?>
      <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>" role="alert">
        <?= htmlspecialchars($flash['message']) ?>
      </div>
    <?php endif; ?>

    <div class="card mb-4">
      <div class="card-header bg-primary text-white">
        <?= $editMision ? "Modificar Misión #{$editMision['id_mision']}" : 'Agregar Nueva Misión' ?>
      </div>
      <div class="card-body">
        <form action="funciones.php" method="POST">
          <?= csrf_input() ?>
          <input type="hidden" name="accion" value="<?= $editMision ? 'modificar_mision' : 'agregar_mision' ?>">
          <?php if ($editMision): ?>
            <input type="hidden" name="id_mision" value="<?= (int) $editMision['id_mision'] ?>">
          <?php endif; ?>
          <div class="mb-3">
            <label>Juego:</label>
            <select name="id_juego" class="form-select" required>
              <option value="">Seleccione un juego</option>
              <?php foreach ($juegos as $j): ?>
                <option value="<?= (int) $j['id_juego'] ?>" <?= $editMision && $editMision['id_juego'] == $j['id_juego'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($j['nombreJuego']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label>Nombre de la Misión</label>
            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($editMision['nombreMision'] ?? '') ?>" required maxlength="150">
          </div>
          <div class="mb-3">
            <label>Descripción</label>
            <textarea class="form-control" name="descripcion" rows="3" required><?= htmlspecialchars($editMision['descripcion'] ?? '') ?></textarea>
          </div>
          <div class="mb-3">
            <label>Recompensa</label>
            <input type="number" class="form-control" name="recompensa" value="<?= htmlspecialchars((string) ($editMision['recompensa'] ?? '')) ?>" min="0" step="0.01" required>
          </div>
          <button class="btn btn-success w-100"><?= $editMision ? 'Guardar Cambios' : 'Agregar Misión' ?></button>
        </form>
      </div>
    </div>

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
                <td><?= (int) $m['id_mision'] ?></td>
                <td><?= htmlspecialchars($m['nombreJuego']) ?></td>
                <td><?= htmlspecialchars($m['nombreMision']) ?></td>
                <td><?= htmlspecialchars($m['descripcion']) ?></td>
                <td>$<?= number_format((float) $m['recompensa'], 2) ?></td>
                <td>
                  <a href="admin.php?edit_mision=<?= (int) $m['id_mision'] ?>" class="btn btn-warning btn-sm">Modificar</a>
                  <form action="funciones.php" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar misión?');">
                    <?= csrf_input() ?>
                    <input type="hidden" name="accion" value="eliminar_mision">
                    <input type="hidden" name="id_mision" value="<?= (int) $m['id_mision'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header bg-success text-white"><?= $editJuego ? "Modificar Juego #{$editJuego['id_juego']}" : 'Agregar Nuevo Juego' ?></div>
      <div class="card-body">
        <form action="funciones.php" method="POST">
          <?= csrf_input() ?>
          <input type="hidden" name="accion" value="<?= $editJuego ? 'modificar_juego' : 'agregar_juego' ?>">
          <?php if ($editJuego): ?>
            <input type="hidden" name="id_juego" value="<?= (int) $editJuego['id_juego'] ?>">
          <?php endif; ?>
          <div class="mb-3">
            <label>Nombre del Juego</label>
            <input type="text" class="form-control" name="nombreJuego" value="<?= htmlspecialchars($editJuego['nombreJuego'] ?? '') ?>" required maxlength="150">
          </div>
          <button class="btn btn-primary w-100"><?= $editJuego ? 'Guardar Cambios' : 'Agregar Juego' ?></button>
        </form>
      </div>
    </div>

    <div class="card mb-5">
      <div class="card-header bg-dark text-white">Juegos Registrados</div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr><th>ID</th><th>Juego</th><th>Acciones</th></tr>
          </thead>
          <tbody>
            <?php foreach ($juegos as $j): ?>
              <tr>
                <td><?= (int) $j['id_juego'] ?></td>
                <td><?= htmlspecialchars($j['nombreJuego']) ?></td>
                <td>
                  <a href="admin.php?edit_juego=<?= (int) $j['id_juego'] ?>" class="btn btn-warning btn-sm">Modificar</a>
                  <form action="funciones.php" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar juego?');">
                    <?= csrf_input() ?>
                    <input type="hidden" name="accion" value="eliminar_juego">
                    <input type="hidden" name="id_juego" value="<?= (int) $j['id_juego'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header bg-secondary text-white">Agregar usuario o administrador</div>
      <div class="card-body">
        <form action="funciones.php" method="POST">
          <?= csrf_input() ?>
          <input type="hidden" name="accion" value="agregar_usuario_admin">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Nombre de usuario</label>
              <input type="text" class="form-control" name="nombreUsuario" required maxlength="100">
            </div>
            <div class="col-md-4">
              <label class="form-label">Correo</label>
              <input type="email" class="form-control" name="Correo" required maxlength="150">
            </div>
            <div class="col-md-2">
              <label class="form-label">Contraseña</label>
              <input type="password" class="form-control" name="contrasena" required minlength="8">
            </div>
            <div class="col-md-2">
              <label class="form-label">Rol</label>
              <select name="rol" class="form-select" required>
                <option value="usuario">Usuario</option>
                <option value="admin">Administrador</option>
              </select>
            </div>
          </div>
          <button class="btn btn-secondary mt-3">Crear cuenta</button>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header bg-dark text-white">Usuarios registrados</div>
      <div class="card-body">
        <table class="table table-bordered table-striped align-middle">
          <thead>
            <tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Cambiar rol</th></tr>
          </thead>
          <tbody>
            <?php foreach ($usuarios as $u): ?>
              <tr>
                <td><?= (int) $u['id_usuario'] ?></td>
                <td><?= htmlspecialchars($u['NombreUsuario']) ?></td>
                <td><?= htmlspecialchars($u['correo']) ?></td>
                <td><?= htmlspecialchars($u['rol']) ?></td>
                <td>
                  <form action="funciones.php" method="POST" class="d-flex gap-2">
                    <?= csrf_input() ?>
                    <input type="hidden" name="accion" value="actualizar_rol_usuario">
                    <input type="hidden" name="id_usuario" value="<?= (int) $u['id_usuario'] ?>">
                    <select name="rol" class="form-select form-select-sm">
                      <option value="usuario" <?= $u['rol'] === 'usuario' ? 'selected' : '' ?>>Usuario</option>
                      <option value="admin" <?= $u['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                    </select>
                    <button class="btn btn-outline-primary btn-sm">Guardar</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <br>
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
