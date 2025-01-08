<?php 
$hoja_de_estilos = "p_operativo.css?v=".time();
$titulo = "Personal Operativo";
include "base.php";
require_once "src/controllers/TrabajadoresController.php";

$listadoPersonalOperativo = $trabajadoresController->mostrarPersonalOperativo();
?>

    <h1>Listado de Personal Operativo</h1>
    <input type="text" id="searchInput" placeholder="Buscar...">
    <table class="tb-principal">
        <thead>
            <tr>
                <th>Item</th>
                <th>Activo</th>
                <th class="th-datos-personales">Apellidos</th>
                <th class="th-datos-personales">Nombres</th>
                <th>DNI</th>
                <th class="th-datos-personales">Cargo</th>
                <th class="th-datos-personales">Área</th>
                <th class="th-datos-personales">Departamento</th>
                <th>Celular</th>
                <th>Correo</th>
                <th>Tipo de Contrato</th>
                <th>Sede</th>
                <th>Modalidad</th>
                <th class='th-telefono'>Telefono</th>
                <th class='th-estado'>Estado</th>
                <!-- columnas de Documentos -->
                <?php if (!empty($listadoPersonalOperativo)): ?>
                    <?php foreach (array_keys($listadoPersonalOperativo[0]) as $col): ?>
                        <?php if (!in_array($col, ['activo', 'apellidos', 'nombres', 'id', 'cargo', 'area', 'departamento', 'celular', 'correo', 'tipo_contrato', 'sede', 'modalidad', 'estado', 'telefono'])): ?>
                            <th class="th-documentos"><?php echo htmlspecialchars($col); ?></th>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody id="tableBody">
            <?php
                //print_r($listadoPersonalOperativo);
                $contador = 1;
                foreach ($listadoPersonalOperativo as $trabajador): ?>
                <tr data-id="<?= $trabajador['id'];?>">
                    <td><?php echo $contador++; ?></td>
                    <td><?php echo htmlspecialchars($trabajador['activo'] ? 'Sí' : 'No'); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['nombres']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['id']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['cargo']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['area']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['departamento']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['celular']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['correo']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['tipo_contrato']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['sede']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['modalidad']); ?></td>
                    <td style="display: none;"><?php echo htmlspecialchars($trabajador['telefono']); ?></td>
                    <td style="display: none;"><?php echo htmlspecialchars($trabajador['estado']); ?></td>
                    <!-- columnas de Documentos -->
                    <?php foreach ($trabajador as $col => $value): ?>
                    <?php if (!in_array($col, ['activo', 'apellidos', 'nombres', 'id', 'cargo', 'area', 'departamento', 'celular', 'correo', 'tipo_contrato', 'estado', 'telefono', 'sede', 'modalidad'])): ?>
                        <?php 
                        $documento = json_decode($value, true); 
                        $archivo = $documento['archivo'] ?? null;
                        $nom_archivo = $documento['nombre_archivo'] ?? null;
                        $fecha = $documento['fecha'] ?? null;
                        $clase = $fecha ? 'con_archivo' : 'sin_archivo';
                        ?>
                        <td class="<?php echo $clase; ?>">
                            <div class="td-documento">
                                <?php if ($archivo): ?>
                                    <div>
                                        <button data-id="<?php echo $trabajador['id'];?>" data-id2="<?php echo $col?>" class="adjuntar-doc">
                                            Adjuntar
                                        </button>
                                    </div>
                                    <div>
                                        <a href="<?php echo htmlspecialchars($archivo); ?>" target="_blank" class="enlace-documento">
                                            <?php echo htmlspecialchars($nom_archivo); ?>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div>
                                        <button data-id="<?php echo $trabajador['id'];?>" data-id2="<?php echo $col?>" class="adjuntar-doc">
                                            Añadir
                                        </button>
                                    </div>
                                    <div>
                                        Inhabilitado
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    <?php endif; ?>
                <?php endforeach; ?>
                </tr>
                
            <?php endforeach; ?>
        </tbody>
    </table>
</aside>
</div>
<!-- modal -->
<div id="modal-archivo" class="modal-oculto">
    <form id="form-adjuntar-archivo" enctype="multipart/form-data" class="modal-content">
        <div class="modal-sup">
            <p>Cargar documento</p>
            <i class="fa-regular fa-circle-xmark fa-2xl" id="btn-cerrar"></i>
        </div>

        <p id="doc">Documento: <span></span></p>
        <p id="trab">Trabajador: <span></span></p>
        <!-- <label for="archivo">Selecciona el archivo:</label> -->
        <br>

        <!-- Seleccionar archivo -->
        <label for="archivo" id="lbl-archivo">Seleccionar archivo</label>
        <input type="file" name="archivo" id="archivo" required style="display: none;">
        <span id="texto-archivo-seleccionado">Ningún archivo seleccionado</span>

        <input type="hidden" name="trabajador_id" id="trabajador_id">
        <input type="hidden" name="doc_name" id="doc_name">
        <div class="modal-botones">
            <button type="submit" id="btn-guardar" class="btn">Guardar <i class="fa-solid fa-floppy-disk"></i></button>
            <!-- <button type="button" id="btn-cerrar" class="btn-cerrar">Cerrar</button> -->
        </div>
    </form>
</div>

<button id="btn-mostrar-documentos" class="btn-mostrar-documentos">
    <i class="fa-regular fa-folder-open"></i>
</button>
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

    const inputArchivo = document.getElementById('archivo');
    const textoArchivo = document.getElementById('texto-archivo-seleccionado');

    inputArchivo.addEventListener('change', () => {
        const archivoSeleccionado = inputArchivo.files[0];
        if (archivoSeleccionado) {
            textoArchivo.textContent = archivoSeleccionado.name;
        } else {
            textoArchivo.textContent = 'Ningún archivo seleccionado';
        }
    });

    let botones = document.querySelectorAll(".adjuntar-doc");
    botones.forEach((e) => {
        e.addEventListener("click", function(){

            // datos para enviar en el formulario
            const trabajadorId = e.getAttribute("data-id");
            const docName = e.getAttribute("data-id2");


            const fila = e.closest("tr");
            const nombreCompleto = fila.cells[3].innerText + " " + fila.cells[2].innerText;
            // datos para mostrar en el formulario
            document.getElementById("doc").querySelector("span").innerText = docName;
            document.getElementById("trab").querySelector("span").innerText = nombreCompleto;

            console.log(trabajadorId);
            console.log(docName);

            // Asignas los valores al formulario
            document.getElementById("trabajador_id").value = trabajadorId;
            document.getElementById("doc_name").value = docName;
            
            //document.getElementById("modal-archivo").style.display = "block";
            document.getElementById("modal-archivo").classList.remove("modal-oculto");
            document.getElementById("modal-archivo").classList.add("modal-visible");
        })
    })

    // Construyendo modal
    function cerrarModal() {
        /*document.getElementById("modal-archivo").style.display = "none";*/
        document.getElementById("modal-archivo").classList.remove("modal-visible");
        document.getElementById("modal-archivo").classList.add("modal-oculto");
    }

    document.getElementById("btn-cerrar").addEventListener("click", cerrarModal);

    document.getElementById("form-adjuntar-archivo").addEventListener("submit", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch('index.php?page=subir_archivo', {
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
                alert('Archivo cargado correctamente.');
                cerrarModal();
            } else {
                alert('Hubo un error al subir el archivo.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    let filas = document.querySelectorAll("#tableBody tr");
    let btnMostrarDocumentos = document.getElementById("btn-mostrar-documentos");
    let filaSeleccionada = null;
    let trabajadorSeleccionado = null;

    filas.forEach((fila) => {
        fila.addEventListener("dblclick", function () {
            // remueve el fondo de la fila previamente seleccionada
            if (filaSeleccionada) {
                filaSeleccionada.classList.remove("fila-seleccionada");
            }

            // coloca el fondo indicando selección
            fila.classList.add("fila-seleccionada");
            filaSeleccionada = fila;

            trabajadorSeleccionado = fila.getAttribute("data-id");

            btnMostrarDocumentos.style.opacity = "1";
            btnMostrarDocumentos.style.visibility = "visible";
        });
    });

    btnMostrarDocumentos.addEventListener("click", function(){
        trabajadorSeleccionado;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = './documentos_trabajador';
        form.target = '_blank';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id_trabajador';
        input.value = trabajadorSeleccionado;

        form.appendChild(input);

        // Añade el formulario al DOM, envíalo y luego elimínalo
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    });
</script>
</body>
</html>