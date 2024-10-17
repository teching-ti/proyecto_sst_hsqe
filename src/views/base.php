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
    <title><?= htmlspecialchars($titulo)?></title>
    <!-- Estilos -->
    <link rel="stylesheet" href="assets/css/<?= htmlspecialchars($hoja_de_estilos) ?>">
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <img src="assets/img/logo_teching.png" alt="logo_tech">
    </header>
    <div class="content">
        <section class="sup-section">
            <aside class="nav-aside">
                <ul class="nav-options">
                    <li id="mostrar-aside" class="lista-enlace"><i class="fa-solid fa-square-caret-right fa-lg"></i></li>
                    <li class="lista-enlace"><a href="inicio"><i class="fa-solid fa-house"></i> Inicio</a></li>
                    <li class="lista-enlace"><a href="personal_administrativo"><i class="fa-solid fa-list"></i> Personal Administrativo</a></li>
                    <li class="lista-enlace"><a href="personal_operativo"><i class="fa-solid fa-list"></i> Personal Operativo</a></li>
                    <li class="lista-enlace"><a href="documentos"><i class="fa-regular fa-folder-open"></i> Documentos</a></li>
                    <li class="lista-enlace"><a href="otros"><i class="fa-solid fa-layer-group"></i> Otros</a></li>
                </ul>
            </aside>
        </section>