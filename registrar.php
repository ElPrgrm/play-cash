<?php
require_once __DIR__ . '/helpers.php';
ensure_session_started();
$flash = get_flash_message();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="index.css">
    <title>Registro Play&cash</title>
</head>
<body>
    <div class="CRegistro">
        <div class="CA_Registro">
            <h1>Registrarse</h1>
            <br>
            <?php if ($flash): ?>
                <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>" role="alert">
                    <?= htmlspecialchars($flash['message']) ?>
                </div>
            <?php endif; ?>
            <form action="funciones.php" method="post">
                <?= csrf_input() ?>
                <input type="hidden" name="accion" value="registro">
                <label>Nombre de usuario</label>
                <input type="text" name="nombreUsuario" required maxlength="100">
                <br>
                <label>Correo</label>
                <input type="email" name="Correo" required maxlength="150">
                <br>
                <label>Contraseña</label>
                <input type="password" name="contrasena" required minlength="8">
                <br>
                <button type="submit" class="btn btn-success w-55">Registrarse</button>
            </form>
        </div>
    </div>
</body>
</html>
