<?php
$hoja_de_estilos = "vbaja_trabajadores.css?v=".time();
$titulo = "Cese de Trabajador";
include "base.php";
require_once "src/controllers/TrabajadoresController.php";
?>

<h1>Cese de Personal</h1>

<!-- formulario para cesar trabajadores -->
<form id="form-cesar-trabajador">
    <h3>Registrar cese de Trabajador</h3>
    <div>
        <label for="documento_trabajador">DNI/ C.EXT.</label>
        <input type="text" class="elemento-form" name="documento_trabajador" id="documento_trabajador" required>
    </div>
    <div>
        <label for="fecha_cese">Fecha de Cese</label>
        <input type="date" class="elemento-form" name="fecha_cese" id="fecha_cese" required>
    </div>
    <div>
        <label for="motivo_cese">Motivo</label>
        <textarea name="motivo_cese" id="motivo_cese" required></textarea>
    </div>
    <div class="container-btn-cesar-trabajador">
        <button type="button" id="btn-cesar-trabajador">Registrar</button>
    </div>
</form>
</aside>
</div>
</main>
<script>
    let fechaActual = new Date().toISOString().split('T')[0];
    document.getElementById("fecha_cese").value = fechaActual;

    document.getElementById("btn-cesar-trabajador").addEventListener("click", function () {
        const documento = document.getElementById("documento_trabajador").value;
        const fechaCese = document.getElementById("fecha_cese").value;
        const motivoCese = document.getElementById("motivo_cese").value;

        if (!documento || !fechaCese || !motivoCese) {
            alert("Por favor complete todos los campos.");
            return;
        }

        fetch("index.php?page=cesar_trabajador", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ documento, fechaCese, motivoCese })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Cese registrado correctamente.");
                location.reload();
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => console.error("Error:", error));
    });

</script>
</body>
</html>