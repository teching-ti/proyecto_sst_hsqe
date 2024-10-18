<?php
$hoja_de_estilos = "signin.css?v=".time();
$titulo = "Registro de Usuarios";

include "base.php";
?>
<main>
    <h2>Registrar Nuevo Usuario (Vista solo disponible para administrador - jvalladares)</h2>
    <div class="form-content">
        <form action="index.php?page=registrar" id="form-register" class="form-register" method="POST">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario"  required>

            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos"  id="apellidos" required>

            <label for="nombres">Nombres:</label>
            <input type="text" name="nombres" id="nombres" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" id="contrasena" required>

            <label for="rol"></label>
            <select name="rol" id="rol" required>
                <option disabled selected>Seleccionar Rol</option>
                <?php foreach($roles as $rol): ?>
                    <option value="<?= $rol['id'];?>"><?= $rol['nombre'];?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" id="btn-registrar">Registrar</button>
        </form>
    </div>
</main>
</body>

</html>
