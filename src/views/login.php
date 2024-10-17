<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar a la Plataforma</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <h2>Iniciar Sesión</h2>
    <form action="index.php?page=login" method="POST">
        <label for="username">Nombre de Usuario o Correo:</label>
        <input type="text" name="username" required>
        
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>
        
        <button type="submit">Ingresar</button>
    </form>
</body>

</html>