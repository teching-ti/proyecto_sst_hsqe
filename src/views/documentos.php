<?php 
$hoja_de_estilos = "documentos.css?v=".time();
$titulo = "Documentos - Registros Obligatorios";
include "base.php";
require_once 'src/controllers/DocumentosController.php';

$categorias = $documentosController->mostrarRegistroDocumentoForm();
$listaDocumentos = $documentosController->mostrarListadoDocumentos();
?>
        <h1>Documentos</h1>
        <div class="form-content">
            <form action="index.php?page=registrar_documento" id="form-agg-documentos" method="POST">
                <h2>Registrar Documento</h2>
                <label for="nombre">Nombre del documento:</label>
                <input type="text" name="nombre" id="nombre" required>
                <label for="catdocumento">Tipo de documento:</label>
                <select name="catdocumento" id="catdocumento" required>
                    <option value="" disabled selected>Seleccionar</option>
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
                        <th class="th-item">Item.</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php
                    $contador = 1;
                    foreach ($listaDocumentos as $element):
                    ?>
                    <tr data-id="<?=$element['id'];?>">
                        <td><?php echo $contador++;?></td>
                        <td><?php echo htmlspecialchars($element['nombre']); ?></td>
                        <td cat-id="<?=$element['id_cat'];?>"><?php echo htmlspecialchars($element['cat_documento']); ?></td>
                    </tr>
                    <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>

        <!-- modal para edición de nombre de documentos -->
        <div id="modal-edit-doc" class="modal-edit-doc">
            <form id="form-edit-doc">
                <div class="modal-sup">
                    <h3>Editar documento</h3>
                    <i class="fa-regular fa-circle-xmark fa-2xl" id="cerrar-editar-modal"></i>
                </div>
                <input type="number" id="doc-id" readonly>
                <label for="doc-name">Nombre de documento</label>
                <textarea type="text" name="doc-name" id="doc-name"></textarea>
                <label for="doc-tipo" class="doc-tipo">Tipo de documento</label>
                <select name="doc-tipo" id="doc-tipo" class="doc-tipo" required>
                    <option disabled selected>Seleccionar</option>
                    <?php foreach($categorias as $cat): ?>
                        <option value="<?= $cat['id'];?>"><?= $cat['nombre'];?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn-edit-doc" id="btn-edit-doc">Guardar</button>
            </form>
        </div>

    </aside>
</div>
<button id="btn-editar-documento" class="btn-editar-documento">
    <i class="fa-solid fa-file-pen"></i>
</button>

<!-- Notificación -->
<div id="notificacion-modal" class="modal-notificacion">
  <div class="modal-notificacion-contenido">
    <p id="modal-mensaje"></p>
    <button id="modal-aceptar">Aceptar</button>
  </div>
</div>

</main>

<script>

    let filas = document.querySelectorAll("#tableBody tr");
    let btnEditarDocumentos = document.getElementById("btn-editar-documento");
    let filaSeleccionada = null;
    let documentoSeleccionado = null;
    let modalEditar = document.getElementById("modal-edit-doc");
    let btnCerrarModal = document.getElementById("cerrar-editar-modal");
    let formEditarDocumento = document.getElementById("form-edit-doc");

    filas.forEach((fila) => {
        fila.addEventListener("dblclick", function () {
            // remueve el fondo de la fila previamente seleccionada
            if (filaSeleccionada) {
                filaSeleccionada.classList.remove("fila-seleccionada");
            }

            // coloca el fondo indicando selección
            fila.classList.add("fila-seleccionada");
            filaSeleccionada = fila;

            documentoSeleccionado = fila.getAttribute("data-id");
            btnEditarDocumentos.style.opacity = "1";
            btnEditarDocumentos.style.visibility = "visible";
        });
    });

    let btnEditarDocumento = document.getElementById("btn-editar-documento");
    btnEditarDocumento.addEventListener("click", function(){
        if(filaSeleccionada){
            let id = filaSeleccionada.getAttribute("data-id");
            let celdas = filaSeleccionada.querySelectorAll("td");

            console.log(id);

            document.getElementById("doc-id").value = id;
            document.getElementById("doc-name").value = celdas[1].textContent.trim();
            document.getElementById("doc-tipo").value = celdas[2].getAttribute("cat-id");
            // colocar aquí el select para poder cargar los demás elementos
            modalEditar.classList.add("modal-activo");
        }
    });

    btnCerrarModal.addEventListener("click", function () {
        modalEditar.classList.remove("modal-activo");
    });

    formEditarDocumento.addEventListener("submit", function(e){
        e.preventDefault();

        let id = parseInt(document.getElementById("doc-id").value);
        let nombreDoc = document.getElementById("doc-name").value;

        fetch("index.php?page=editar_documento", {
            method: "POST",
            body: JSON.stringify({ id, nombreDoc}),
            headers: { "Content-Type": "application/json" }
        })
        .then(response => response.json())
        .then(data => {

            const modal = document.getElementById("notificacion-modal");
            const mensaje = document.getElementById("modal-mensaje");
            const btnAceptar = document.getElementById("modal-aceptar");

            if (data.success) {
                //alert("Trabajador actualizado con éxito.");
                mensaje.textContent = "Documento actualizado con éxito";
                //location.reload();
            } else {
                mensaje.textContent = "Error al actualizar datos del documento"
                //alert("Error al actualizar el trabajador.");
            }

            modal.style.display = "block";

            btnAceptar.addEventListener("click", () => {
                modal.style.display = "none";
                location.reload();
            });
        })
        .catch(error => console.error("Error:", error));
    });
    
</script>
</body>
</html>