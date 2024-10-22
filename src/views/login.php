<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Desactivar Cache de los Navegadores -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Ingresar a la Plataforma</title>
    <!-- Estilos -->
    <link rel="stylesheet" href="assets/css/login.css">
    <!-- FavIcon -->
    <link rel="shortcut icon" href="assets/img/favicon-teching.png" type="image/x-icon">
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <img src="assets/img/logo_teching.png" alt="logo_tech">
    </header>
    <h2>Plataforma de Registros Obligatorios de SST por Trabajador</h2>
    <h3>Iniciar Sesión</h3>
    <div class="form-content">
        <form action="index.php?page=login" class="form-login" method="POST">
            <label for="usuario">Nombre de Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            
            <label for="contrasena">Contraseña:</label>
            <input type="password"  id="contrasena" name="contrasena" required>
            
            <button type="submit" class="btn-ingresar">Ingresar</button>
        </form>
    </div>
</body>

</html>