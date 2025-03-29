<?php
require_once "src/controllers/TrabajadoresController.php";
// se obtienen los datos
$input = json_decode(file_get_contents("php://input"), true);
$ids = $input['ids'] ?? [];

// se que haya IDs para evitar una consulta vacía
if (empty($ids)) {
    exit("No hay trabajadores visibles para descargar.");
}

// instancia del controlador
$trabajadoresController = new TrabajadoresController($conn);

// lista filtrada
$listadoPersonal = $trabajadoresController->mostrarTrabajadores($ids);

// se establecen los encabezados para la descarga del archivo CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=personal.csv');

// codificación UTF-8 para el archivo resultante
echo "\xEF\xBB\xBF";

// se abre la salida de PHP como un "falso" archivo para escribir el CSV
$output = fopen('php://output', 'w');

// fila de encabezados
fputcsv($output, [
    'Item', 'Estado', 'Apellidos', 'Nombres', 'DNI', 'Fecha de Ingreso','Cargo', 'Área', 'Departamento', '# de Celular', 'Correo', 'Tipo de Contrato', 'Modalidad', 'Sede', 'Teléfono'
]);

// datos de cada trabajador en el CSV
$contador = 1;
foreach ($listadoPersonal as $trabajador) {
    fputcsv($output, [
        $contador++,
        $trabajador['activo'] ? 'Activo' : 'Cesado',
        $trabajador['apellidos'],
        $trabajador['nombres'],
        $trabajador['id'],
        $trabajador['fecha_ingreso'],
        $trabajador['cargo'],
        $trabajador['area'],
        $trabajador['departamento'],
        $trabajador['celular'],
        $trabajador['correo'],
        $trabajador['tipo_contrato'],
        $trabajador['modalidad_nombre'],
        $trabajador['sede_nombre'],
        $trabajador['telefono']
    ]);
}

// cerrando archivo de salida
fclose($output);
exit;