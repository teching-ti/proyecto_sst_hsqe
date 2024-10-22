<?php 
$hoja_de_estilos = "main.css?v=".time();
$titulo = "Inicio";

include "base.php";
?>
        <main>
            <h2 style="font-weight: bold;">Se trata de un...</h2>
            <p>Inicio</p>
            <?php if($usuario_logueado):?>
                <p>Usuario: <?= $_SESSION['usuario']; ?></p>
                <p>Nombres: <?= $_SESSION['nombres']; ?></p>
                <p>Apellidos: <?= $_SESSION['apellidos']; ?></p>
                <a href="index.php?page=salir">Cerrar Sesión</a>
            <?php else:?>
                <p>No hay sesiones activas</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>