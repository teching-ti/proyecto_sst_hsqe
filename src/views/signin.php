<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <h2>Registrar Nuevo Usuario</h2>
    <form action="index.php?page=registrar" method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" required>

        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" required>

        <label for="nombres">Nombres:</label>
        <input type="text" name="nombres" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required>

        <label for="rol"></label>
        <select name="rol" id="rol">
            <option>Rol</option>
            <option value="1">Admin</option>
        </select>

        <button type="submit">Registrar</button>
    </form>
</body>

</html>
