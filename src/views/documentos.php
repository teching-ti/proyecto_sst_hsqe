<?php 
$hoja_de_estilos = "documentos.css?v=".time();
$titulo = "Documentos - Registros Obligatorios";
include "base.php";
?>
<main>
    <h2>Registrar Documentos</h2>
    <div class="form-content">
        <form action="index.php?page=registrar_documento">
            <label for="nombre">Nombre del documento:</label>
            <input type="text" name="nombre" id="nombre" required>

            <label for="catdocumento">Tipo Documento:</label>
            <select name="catdocumento" id="catdocumento" required>
                <option disabled selected>Seleccionar</option>
                <?php foreach($categorias as $cat): ?>
                    <option value="<?= $cat['id'];?>"><?= $cat['nombre'];?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</main>
</body>

</html>