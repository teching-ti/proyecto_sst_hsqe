<?php 
$hoja_de_estilos = "historial_trabajadores.css?v=".time();
$titulo = "Historial del Personal";
include "base.php";
require_once "src/controllers/TrabajadoresController.php";

$mes = isset($_POST['mes']) ? intval($_POST['mes']) : intval(date('m'));
$anio = isset($_POST['anio']) ? intval($_POST['anio']) : date('Y');

$trabajadores = $trabajadoresController->obtenerTrabajadoresPorFecha($mes, $anio);

$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
?>

<h1>Seleccionar Fechas</h1>

<form method="POST" action="" class="form-filtro">
    <div>
        <label for="mes">Mes:</label>
        <select id="mes" name="mes">
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i ?>" <?= $i == $mes ? 'selected' : '' ?>>
                    <?= $meses[$i] ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>
    
    <div>
        <label for="anio">Año:</label>
        <select id="anio" name="anio">
            <?php 
            $anio_actual = date('Y');
            for ($i = 2012; $i <= $anio_actual; $i++): ?>
                <option value="<?= $i ?>" <?= $i == $anio ? 'selected' : '' ?>>
                    <?= $i ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>

    <button type="submit" id="btn-filtrar">Cargar <i class="fa-solid fa-calendar-days"></i></button>
</form>

<?php if (!empty($trabajadores)): ?>
    <h2>Resultados para <?= $meses[$mes] ?> <?= $anio ?></h2>
    <div class="checkbox-group">
        <label>
            <input type="checkbox" name="mostrar_ingreso" id="check_ingreso" value="1" checked>
            Ingreso
        </label>
        <label>
            <input type="checkbox" name="mostrar_cese" id="check_cese" value="1" checked>
            Cese
        </label>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Cargo</th>
                <th>Área</th>
                <th>Departamento</th>
                <th>Fecha (año-mes-día)</th>
                <th>Acción</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <?php foreach ($trabajadores as $trabajador): ?>
                <tr data-tipo-movimiento="<?= htmlspecialchars($trabajador['tipo_movimiento']) ?>">
                    <td><?= htmlspecialchars($trabajador['nombres']) ?></td>
                    <td><?= htmlspecialchars($trabajador['apellidos']) ?></td>
                    <td><?= htmlspecialchars($trabajador['cargo']) ?></td>
                    <td><?= htmlspecialchars($trabajador['area']) ?></td>
                    <td><?= htmlspecialchars($trabajador['departamento']) ?></td>
                    <td><?= htmlspecialchars(!empty($trabajador['fecha']) ? date('Y-m-d', strtotime($trabajador['fecha'])) : '') ?></td>
                    <td><?= htmlspecialchars($trabajador['tipo_movimiento']) ?></td>
                    <td><?= htmlspecialchars($trabajador['motivo']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <h2>No se encontraron trabajadores para el mes y año seleccionados.</h2>
<?php endif; ?>
</aside>
</div>
</main>

<script>
    const checkIng = document.getElementById("check_ingreso");
    const checkCese = document.getElementById("check_cese");

    function filtrarFilas() {
        const mostrarIngreso = checkIng.checked;
        const mostrarCese = checkCese.checked;
        const filas = document.querySelectorAll('#tableBody tr');
        
        // establece los estilos en diferentes filas para mostrar u ocultarlas
        filas.forEach(fila => {
            const tipoMovimiento = fila.getAttribute('data-tipo-movimiento').toLowerCase();
            if ((mostrarIngreso && tipoMovimiento === 'ingreso') || (mostrarCese && tipoMovimiento === 'cese')) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    }

    // evita que ambos checkboxes se desmarquen
    checkIng.addEventListener("change", function() {
        if (!this.checked && !checkCese.checked) {
            this.checked = true;
            alert("Debe haber al menos un tipo de movimiento seleccionado.");
        }
        filtrarFilas();
    });

    checkCese.addEventListener("change", function() {
        if (!this.checked && !checkIng.checked) {
            this.checked = true;
            alert("Debe haber al menos un tipo de movimiento seleccionado.");
        }
        filtrarFilas();
    });

    // filtro tras cargar la página
    window.addEventListener('load', function() {
        // ambos checkbox estarán marcados por defecto
        checkIng.checked = true;
        checkCese.checked = true;
        filtrarFilas();
    });
</script>
</body>
</html>