<?php
require_once __DIR__ . '/helpers.php';
ensure_session_started();
if (current_user()) {
    redirect(is_admin_user() ? 'admin.php' : 'pantalla principal.php');
}
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
    <title>PLAY&CASH</title>
</head>
<body>
    <div class="C_Inicio_sesion">
        <div class="CA_inicio_sesion">
            <h1>Inicio de sesion</h1>
            <br>
            <?php if ($flash): ?>
                <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>" role="alert">
                    <?= htmlspecialchars($flash['message']) ?>
                </div>
            <?php endif; ?>
            <img src="Logo.png" alt="Logo" height="100" width="100">
            <form action="funciones.php" method="post">
                <?= csrf_input() ?>
                <input type="hidden" name="accion" value="login">
                <label>Correo</label>
                <input type="email" name="Correo" required maxlength="150">
                <br>
                <label>Contraseña</label>
                <input type="password" name="contrasena" required minlength="8">
                <br>
                <button type="submit" class="btn btn-success">Iniciar sesion</button>
            </form>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='registrar.php'">Registrar</button>
        </div>
    </div>
</body>
</html>
