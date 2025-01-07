<?php 
$hoja_de_estilos = "vdocumentos_trabajador.css?v=".time();
$titulo = "Historial de documentos";
include "base.php";
require_once "src/controllers/DocumentoTrabajadorController.php";

$id_trabajador = isset($_POST['id_trabajador']) ? strval($_POST['id_trabajador']) : 0;
$listadoDocumentosPersonal = $documentoTrabajadorController->obtenerDocumentosPorTrabajador($id_trabajador);
$nombreTrabajador = $documentoTrabajadorController->obtenerNombreTrabajador($id_trabajador);

// obtener nombres de documentos por ID
$nombresDocumentos = [];

foreach ($listadoDocumentosPersonal as $doc) {
    $id_documento = $doc['id_documento'];
    if (!isset($nombresDocumentos[$id_documento])) {
        // Consulta el nombre del documento (puedes ajustarlo según tu controlador)
        $nombresDocumentos[$id_documento] = $documentoTrabajadorController->obtenerNombreDocumento($id_documento);
    }
}

?>

    <h2 class="nombre-trabajador">Documentos - <?= htmlspecialchars($nombreTrabajador) ?></h2>
    <?php
    // Agrupar documentos por tipo
    $documentosAgrupados = [];

    foreach ($listadoDocumentosPersonal as $doc) {
        $id_documento = $doc['id_documento'];

        if (!isset($documentosAgrupados[$id_documento])) {
            $documentosAgrupados[$id_documento] = [];
        }
        $documentosAgrupados[$id_documento][] = $doc;
    }

    // Generar una tabla por tipo de documento
    if (!empty($documentosAgrupados)) {
        foreach ($documentosAgrupados as $id_documento => $documentos) {
            $nombre_documento = isset($nombresDocumentos[$id_documento]) ? $nombresDocumentos[$id_documento] : "Desconocido";
            echo "<div class='documento-conenedor'>";
            echo "<h4 class='nombre-documento'>" . htmlspecialchars($nombre_documento) . "</h4>";
            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th class='th-item'>#</th>";
            echo "<th class='th-nombre-archivo'>Nombre del Archivo</th>";
            echo "<th class='th-fecha-subida'>Fecha de Subida</th>";
            echo "<th class='th-archivo-eval'>Archivo Evaluado</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            $contador = 1;
            foreach ($documentos as $doc) {
                echo "<tr>";
                echo "<td>" . $contador++ . "</td>";
                echo "<td>" . htmlspecialchars($doc['nombre_archivo']) . "</td>";
                echo "<td>" . htmlspecialchars($doc['fecha_subida']) . "</td>";
                echo "<td>" . (!empty($doc['archivo_eval']) ? "<a href='" . htmlspecialchars($doc['archivo_eval']) . "' target='_blank' class='btn-documento'>Ver archivo</a>" : "No disponible") . "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        }
    } else {
        echo "<p>No se encontraron documentos para este trabajador.</p>";
    }

    ?>

</aside>
</div>
</main>


