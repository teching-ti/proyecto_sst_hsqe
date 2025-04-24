<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Desactivar indexado -->
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Desactivar Cache de los Navegadores -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <!-- Page tiitle -->
    <title><?= htmlspecialchars($titulo)?></title>
    <!-- Estilos -->
     <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/<?= htmlspecialchars($hoja_de_estilos) ?>">
    <!-- FavIcon -->
    <link rel="shortcut icon" href="assets/img/favicon-teching.png" type="image/x-icon">
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <!-- Jquery -->
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script> -->

    <script src="assets/js/main.js"></script>
</head>
<body>
    <main>

        <header class="header-responsive">
            <div class="header-container">
                <img src="assets/img/logo_color_teching.png" alt="logo" class="logo-responsive">
                <button id="menu-toggle" class="menu-hamburguesa">
                    <i class="fa-solid fa-bars fa-xl"></i>
                </button>
            </div>
        </header>

    <div class="content">
        <aside class="nav-aside">
        <div class="nav-header">
            <img src="assets/img/logo_color_teching.png" alt="logo_tech" class="logo">
            <!-- <i class="fa-regular fa-circle-xmark fa-xl" id="btn-cerrar-nav"></i> -->
        </div>
            <ul class="nav-options">
                <div id="elementos-aside">
                    <li class="lista-enlace"><a href="inicio"><i class="fa-solid fa-house"></i> Inicio</a></li>
                    <li class="lista-enlace"><a href="trabajadores"><i class="fa-solid fa-users-line"></i> Personal de la empresa</a></li>
                    <li class="lista-enlace"><a href="personal_administrativo"><i class="fa-solid fa-list"></i> Personal Administrativo</a></li>
                    <li class="lista-enlace"><a href="personal_operativo"><i class="fa-solid fa-list"></i> Personal Operativo</a></li>
                    <?php
                        if($_SESSION['rol']==1 or $_SESSION['rol']==3){
                        echo "<li class='lista-enlace'><a href='trabajadores_cese' target='_blank'><i class='fa-solid fa-user-minus'></i> Cese de Personal</a></li>";
                        }
                    ?>
                    <li class="lista-enlace"><a href="historial_trabajadores"><i class="fa-solid fa-user-clock"></i> Historial del Personal</a></li>
                    <?php
                        if($_SESSION['rol']==1 or $_SESSION['rol']==2){
                        echo "<li class='lista-enlace'><a href='documentos'><i class='fa-regular fa-folder-open'></i> Documentos</a></li>";
                        }
                    ?>
                    <?php
                        if($_SESSION['rol']==1){
                        echo "<li class='lista-enlace'><a href='signin'><i class='fa-solid fa-user-plus'></i> Registrar usuario</a></li>";
                        }
                    ?>
                    <li class="lista-enlace"><a href="salir"><i class="fa-solid fa-door-open"></i> Salir</a></li>
                </div>
            </ul>
        </aside>
        <aside id="principal">