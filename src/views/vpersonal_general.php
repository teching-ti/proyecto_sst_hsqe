<?php
$hoja_de_estilos = "p_general.css?v=".time();
$titulo = "Personal de la Organización";
include "base.php";
require_once "src/controllers/TrabajadoresController.php";

$listadoPersonal = $trabajadoresController->mostrarTrabajadores();

//las opciones filtros deben ser iguales a las opciones de creación de usuario
?>

<aside id="principal">
    <h1>Base de Datos del Personal</h1>
    <a href="#" class="btn-descargar" id="btnDescargar"><i class="fa-solid fa-download"></i></a>
    <input type="text" id="searchInput" placeholder="Buscar...">

<?php if (!empty($listadoPersonal)): ?>
    <table class="tb-principal">
        <thead>
            <tr>
                <th class="th-item">Item</th>
                <th style="display: none;">estado valor</th>
                <th class="th-estado">Estado</th>
                <th style="display: none;">tipo valor</th>
                <th style="display: none;" class="th-tipo" >Tipo</th>
                <th class="th-apellido">Apellidos</th>
                <th class="th-nombre">Nombres</th>
                <th class="th-dni">DNI.</th>
                <th class="th-cargo">Cargo</th>
                <th class="th-area">Área</th>
                <th class="th-dep">Departamento</th>
                <th class="th-cel">Celular</th>
                <th class="th-ingreso">Fecha de Ingreso</th>
                <th class="th-correo">Correo</th>
                <th class="th-contrato">Tipo de Contrato</th>
                <th class="th-telefono">Teléfono</th>
            </tr>
            <!-- style="display: none;" -->
        </thead>
        <tbody id="tableBody">
            <?php 
            $contador = 1;
            foreach ($listadoPersonal as $trabajador): ?>
                <tr data-id="<?= $trabajador['id']; ?>">
                    <td><?= $contador++; ?></td>
                    <td style="display: none;"><?php echo htmlspecialchars($trabajador['activo']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['activo'] ? 'Activo' : 'Cesado'); ?></td> <!-- activo o cesado -->
                    <td style="display: none;"><?php echo htmlspecialchars($trabajador['id_tipo']) ?></td>
                    <td style="display: none;"><?php echo htmlspecialchars($trabajador['id_tipo']==1 ? "Administrativo" : "Operativo")  ?></td> <!-- Administrativo u operativo -->
                    <td><?php echo htmlspecialchars($trabajador['apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['nombres']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['id']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['cargo']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['area']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['departamento']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['celular']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['fecha_ingreso']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['correo']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['tipo_contrato']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['telefono']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No se encontraron registros de trabajadores.</p>
<?php endif; ?>
</aside>

<!-- formulario para agregar trabajadores -->
<div id="modal-agregar-trabajador" class="modal-oculto">
    <form id="form_insertar_trabajador" class="modal-content" >
        <div class="modal-sup">
            <p>Añadir trabajador</p>
            <i class="fa-regular fa-circle-xmark fa-2xl" id="cerrar-modal"></i>
        </div>
        <div class="form-containers">
            <div>
                <input type="text" id="dni" name="dni" placeholder="DNI/ C.E..." required>
                <select name="id_tipo" id="id_tipo" required>
                    <option disabled selected>Tipo Trabajador</option>
                    <option value="1">Administrativo</option>
                    <option value="2">Operativo</option>
                </select>
            </div>
            <div>
                <input type="text" id="nombres" name="nombres" placeholder="Nombres..." required>
                <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos..." required>
            </div>
        </div>
        <div class="form-containers">
            <input type="text" id="cargo" name="cargo" placeholder="Cargo..." required>
            <select name="area" id="area" required>
                <option disabled selected>Área</option>
                <option value="Administración y Finanzas">Administración y Finanzas</option>
                <option value="Comercial y Licitaciones">Comercial y Licitaciones</option>
                <option value="Gerencia General">Gerencia General</option>
                <option value="HSQE">HSQE</option>
                <option value="ID&QA">ID&QA</option>
                <option value="Legal">Legal</option>
                <option value="Operaciones y Proyectos">Operaciones y Proyectos</option>
                <option value="Planeamiento y Control">Planeamiento y Control</option>
            </select>
             <select name="departamento" id="departamento" required>
                <option disabled selected>Departamento</option>
                <option value="Administración">Administración</option>
                <option value="Comercial y Licitaciones">Comercial y Licitaciones</option>
                <option value="Gerencia General">Gerencia General</option>
                <option value="Gestión Energética">Gestión Energética</option>
                <option value="HSQE">HSQE</option>
                <option value="ID&QA">ID&QA</option>
                <option value="Legal">Legal</option>
                <option value="Planeamiento y Control">Planeamiento y Control</option>
                <option value="Proyectos">Proyectos</option>
             </select>
            <div>
                <label for="fecha_ingreso">Ingreso</label>
                <input type="date" id="fecha_ingreso" name="fecha_ingreso" placeholder="22/11/2024" required>
                <input type="text" id="estado" name="estado" placeholder="Estado">
                <!--Evaluar ya que depende de lo que esté activo, se podría usar para dar por eliminado a un usuario -->
            </div>
        </div>

        <div class="form-containers">
            <div>
                <select id="activo" name="activo" required>
                    <option disabled selected>Activo/ Inactivo</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
                <input id="celular" name="celular" placeholder="Celular...">
            </div>
            <input id="correo" name="correo" placeholder="Correo..." required>
            <div>
                <input id="tipo_contrato" name="tipo_contrato" placeholder="Tipo de Contrato..." required>
                <input id="telefono" name="telefono" type="text" placeholder="Teléfono">
            </div>
        </div>

        <button type="submit" id="btn_insertar_trabajador">Guardar <i class="fa-regular fa-floppy-disk"></i></button>
    </form>
</div>

<!-- formulario para editar trabajadores -->
<div id="modal-editar-trabajador" class="modal-editar">
    <form id="form-editar-trabajador" class="modal-editar-contenido">
        <div class="modal-sup">
            <p>Editar Trabajador</p>
            <i class="fa-regular fa-circle-xmark fa-2xl" id="cerrar-editar-modal"></i>
        </div>
        <div class="form-section">
            <div>
                <label for="edit-activo">Estado</label>
                <select id="edit-activo" name="edit-activo" required>
                    <option disabled selected>Activo/ Inactivo</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
            <div>
                <label for="edit-id-tipo">Tipo</label>
                <select name="edit-id-tipo" id="edit-id-tipo" required>
                    <option disabled selected>Tipo Trabajador</option>
                    <option value="1">Administrativo</option>
                    <option value="2">Operativo</option>
                </select>
            </div>
        </div>
        <div class="form-section">
            <div>
                <input type="hidden" id="trabajador-id" readonly>
                <label for="edit-nombres">Nombres:</label>
                <input type="text" id="edit-nombres" name="edit-nombres">
            </div>
            <div>
                <label for="edit-apellidos">Apellidos:</label>
                <input type="text" id="edit-apellidos" name="edit-apellidos">
            </div>
        </div>
        <div class="form-section">
            <div>
                <label for="edit-cargo">Cargo:</label>
                <input type="text" id="edit-cargo" name="edit-cargo">
            </div>
            <div>
                <label for="edit-area">Área: </label>
                <select name="edit-area" id="edit-area" required>
                    <option value="Administración y Finanzas">Administración y Finanzas</option>
                    <option value="Comercial y Licitaciones">Comercial y Licitaciones</option>
                    <option value="Gerencia General">Gerencia General</option>
                    <option value="HSQE">HSQE</option>
                    <option value="ID&QA">ID&QA</option>
                    <option value="Legal">Legal</option>
                    <option value="Operaciones y Proyectos">Operaciones y Proyectos</option>
                    <option value="Planeamiento y Control">Planeamiento y Control</option>
                </select>
            </div>
            <div>
                <label for="edit-departamento">Departamento: </label>
                <select name="edit-departamento" id="edit-departamento" required>
                    <option disabled selected>Departamento</option>
                    <option value="Administración">Administración</option>
                    <option value="Comercial y Licitaciones">Comercial y Licitaciones</option>
                    <option value="Gerencia General">Gerencia General</option>
                    <option value="Gestión Energética">Gestión Energética</option>
                    <option value="HSQE">HSQE</option>
                    <option value="ID&QA">ID&QA</option>
                    <option value="Legal">Legal</option>
                    <option value="Planeamiento y Control">Planeamiento y Control</option>
                    <option value="Proyectos">Proyectos</option>
                </select>
            </div>
        </div>

        <div class="form-section">
            <div>
                <label for="edit-celular">Celular:</label>
                <input type="text" id="edit-celular" name="edit-celular">
            </div>
            <div>
                <label for="edit-correo">Correo:</label>
                <input type="text" id="edit-correo" name="edit-correo">
            </div>
            <div>
                <label for="edit-telefono">Telefono:</label>
                <input type="text" id="edit-telefono" name="edit-telefono">
        
            </div>
        </div>
        <div class="form-section">
            <div>
                <label for="edit-fecha-ingreso">Ingreso</label>
                <input type="date" id="edit-fecha-ingreso" name="edit-fecha-ingreso" required>
            </div>
            <div>
                <label for="edit-tipo-contrato">Tipo de Contrato</label>
                <input id="edit-tipo-contrato" name="edit-tipo-contrato" placeholder="Tipo de Contrato..." required>
            </div>
        </div>

        <button type="submit" class="btn-editar-trabajador">Guardar Cambios</button>

    </form>
</div>

<!-- Notificación -->
<div id="notificacion-modal" class="modal-notificacion">
  <div class="modal-notificacion-contenido">
    <p id="modal-mensaje"></p>
    <button id="modal-aceptar">Aceptar</button>
  </div>
</div>

<button id="btn-agregar-trabajador" class="btn-trabajador-opcion">
    <i class="fa-solid fa-user-plus"></i>
</button>

<button id="btn-editar-trabajador" class="btn-trabajador-opcion">
    <i class="fa-solid fa-user-pen"></i>
</button>
</div>
</main>

<script>
    // función de búsqueda
    document.getElementById("searchInput").addEventListener("input", function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll("#tableBody tr");

        rows.forEach(row => {
            // convierte el texto de la fila en un solo string para buscar coincidencias en cualquier columna
            const rowText = row.textContent.toLowerCase();
            // muestra u oculta la fila según si coincide o no con el filtro
            row.style.display = rowText.includes(filter) ? "" : "none";
        });
    });
    
    // descargar en csv
    document.getElementById("btnDescargar").addEventListener("click", function(e){
        e.preventDefault();
        const idVisibles = [];

        document.querySelectorAll("#tableBody tr").forEach(row => {
            if (row.style.display !== "none") {
                const id = row.cells[4].textContent.trim();
                idVisibles.push(id);
            }
        });

        fetch("index.php?page=descargar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ ids: idVisibles })
        })
        .then(response => response.blob())
        .then(blob => {
            // crear un enlace para descargar el archivo
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "trabajadores_visibles.csv";
            document.body.appendChild(a);
            a.click();
            a.remove();
        })
        .catch(error => console.error("Error al descargar:", error));
    });

    let cerrarModal = function(){
        document.getElementById("modal-agregar-trabajador").classList.remove("modal-visible");
        document.getElementById("modal-agregar-trabajador").classList.add("modal-oculto");
    }

    document.getElementById("cerrar-modal").addEventListener("click", cerrarModal);

    document.getElementById("form_insertar_trabajador").addEventListener("submit", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch('index.php?page=insertar_trabajador', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log(response);
            return response.json();
        })
        .then(data => {

            console.log(data);
            
            if (data.success) {
                alert('Trabajador agregado correctamente.');
                cerrarModal();
                location.reload();
            } else {
                alert(`Error: ${data.message}`);
                //alert('Hubo un error al agregar al nuevo trabajador.');
            }
        })
        .catch(error => {
            cerrarModal();
            console.error('Error:', error);
        });
    });

    document.getElementById("btn-agregar-trabajador").addEventListener("click", function(e){
        e.preventDefault();
        document.getElementById("modal-agregar-trabajador").classList.remove("modal-oculto");
        document.getElementById("modal-agregar-trabajador").classList.add("modal-visible");
    });

    // funcionalidad para editar
    let filas = document.querySelectorAll("#tableBody tr");
    let btnEditarTrabajador = document.getElementById("btn-editar-trabajador");
    let filaSeleccionada = null;

    filas.forEach((fila) => {
        fila.addEventListener("dblclick", function () {
            // remueve el fondo de la fila previamente seleccionada
            if (filaSeleccionada) {
                filaSeleccionada.classList.remove("tr-seleccionada");
            }

            // coloca el fondo indicando selección
            fila.classList.add("tr-seleccionada");
            filaSeleccionada = fila;

            // habilita el botón de editar
            btnEditarTrabajador.style.opacity = "1";
            btnEditarTrabajador.style.visibility = "visible";
        });
    });
    
    let modalEditar = document.getElementById("modal-editar-trabajador");
    let btnCerrarModal = document.getElementById("cerrar-editar-modal");
    let formEditarTrabajador = document.getElementById("form-editar-trabajador");

    // activar modal tras presional el btn de editar usuario
    btnEditarTrabajador.addEventListener("click", function () {
        if (filaSeleccionada) {
            let id = filaSeleccionada.getAttribute("data-id");
            let celdas = filaSeleccionada.querySelectorAll("td");

            // completando lo scampos del formulario
            document.getElementById("trabajador-id").value = id;
            document.getElementById("edit-activo").value = celdas[1].textContent.trim();
            document.getElementById("edit-id-tipo").value = celdas[3].textContent.trim();
            document.getElementById("edit-nombres").value = celdas[6].textContent.trim();
            document.getElementById("edit-apellidos").value = celdas[5].textContent.trim();
            document.getElementById("edit-cargo").value = celdas[8].textContent.trim();
            document.getElementById("edit-area").value = celdas[9].textContent.trim();// select
            document.getElementById("edit-departamento").value = celdas[10].textContent.trim();
            document.getElementById("edit-celular").value = celdas[11].textContent.trim();
            document.getElementById("edit-fecha-ingreso").value = celdas[12].textContent.trim();
            document.getElementById("edit-correo").value = celdas[13].textContent.trim();
            document.getElementById("edit-tipo-contrato").value = celdas[14].textContent.trim();
            document.getElementById("edit-telefono").value = celdas[15].textContent.trim();
            modalEditar.classList.add("modal-activo");
        }
    });

    // cerrar el modal de edición
    btnCerrarModal.addEventListener("click", function () {
        modalEditar.classList.remove("modal-activo");
    });

    // enviar datos del formulario al servidor, json
    formEditarTrabajador.addEventListener("submit", function (e) {
        e.preventDefault();

        let id = document.getElementById("trabajador-id").value;
        let activo = document.getElementById("edit-activo").value;
        let id_tipo = document.getElementById("edit-id-tipo").value;
        let nombres = document.getElementById("edit-nombres").value;
        let apellidos = document.getElementById("edit-apellidos").value;
        let cargo = document.getElementById("edit-cargo").value;
        let area = document.getElementById("edit-area").value;
        let departamento = document.getElementById("edit-departamento").value;
        let celular = document.getElementById("edit-celular").value;
        let fecha_ingreso = document.getElementById("edit-fecha-ingreso").value;
        let correo = document.getElementById("edit-correo").value;
        let tipo_contrato = document.getElementById("edit-tipo-contrato").value;
        let telefono = document.getElementById("edit-telefono").value;

        fetch("index.php?page=editar_trabajador", {
            method: "POST",
            body: JSON.stringify({ id, activo, id_tipo, nombres, apellidos, cargo, area, departamento, celular, fecha_ingreso, correo, tipo_contrato, telefono}),
            headers: { "Content-Type": "application/json" }
        })
        .then(response => response.json())
        .then(data => {

            const modal = document.getElementById("notificacion-modal");
            const mensaje = document.getElementById("modal-mensaje");
            const btnAceptar = document.getElementById("modal-aceptar");

            if (data.success) {
                //alert("Trabajador actualizado con éxito.");
                mensaje.textContent = "Trabajador actualizado con éxito";
                //location.reload();
            } else {
                mensaje.textContent = "Error al actualizar datos del trabajador"
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