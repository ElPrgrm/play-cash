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

            <form action="funciones.php" method="post">

             <input type="hidden" name="accion" value="registro">
           
            <label>Nombre de usuario</label>
            <input type="text" name="nombreUsuario" required>
            <br>
            <label>Correo</label>
            <input type="text" name="Correo" required>
            <br>
            <label>Contraseña</label>
            <input type="password" name="contrasena" required>
            <br>
            <button type="submit" class="btn btn-success w-55">Registrarse</button>
            </form>
             
            
            

        </div>
    </div>
</body>

</html>