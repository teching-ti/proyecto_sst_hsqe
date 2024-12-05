<?php 
$hoja_de_estilos = "documentos.css?v=".time();
$titulo = "Documentos - Registros Obligatorios";
include "base.php";
require_once 'src/controllers/DocumentosController.php';

$categorias = $documentosController->mostrarRegistroDocumentoForm();
$listaDocumentos = $documentosController->mostrarListadoDocumentos();
?>
    <aside id="principal">
        <h1>Documentos</h1>
        <div class="form-content">
            <form action="index.php?page=registrar_documento" id="form-agg-documentos" method="POST">
                <h2>Registrar Documento</h2>
                <label for="nombre">Nombre del documento:</label>
                <input type="text" name="nombre" id="nombre" required>
                <label for="catdocumento">Tipo de documento:</label>
                <select name="catdocumento" id="catdocumento" required>
                    <option disabled selected>Seleccionar</option>
                    <?php foreach($categorias as $cat): ?>
                        <option value="<?= $cat['id'];?>"><?= $cat['nombre'];?></option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit" id="btn-registrar">Registrar</button>
            </form>
        </div>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Item.</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $contador = 1;
                    foreach ($listaDocumentos as $element):
                    ?>
                    <tr>
                        <td><?php echo $contador++;?></td>
                        <td><?php echo htmlspecialchars($element['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($element['cat_documento']); ?></td>
                    </tr>
                    <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </aside>
</div>
</main>

</body>
</html>