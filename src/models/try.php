public function getPersonalAdministrativoConDocumentos() {
    // Obtiene los nombres de los documentos asociados a la categoría 1
    $docQuery = "SELECT id, nombre FROM tb_documentos WHERE cat_documento = 1";
    $stmt = $this->conn->prepare($docQuery);
    $stmt->execute();
    $documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Crear consulta dinámica para seleccionar columnas de los documentos
    $selectDocumentos = "";
    foreach ($documentos as $doc) {
        $selectDocumentos .= "
            (SELECT archivo_eval 
             FROM tb_doctrabajadores 
             WHERE id_trabajador = tb_trabajadores.id 
               AND id_documento = {$doc['id']}
            ) AS '{$doc['nombre']}_archivo', 
            (SELECT fecha_creacion 
             FROM tb_doctrabajadores 
             WHERE id_trabajador = tb_trabajadores.id 
               AND id_documento = {$doc['id']}
            ) AS '{$doc['nombre']}_fecha', ";
    }

    // Consulta principal incluyendo las columnas dinámicas de documentos
    $query = "
        SELECT activo, apellidos, nombres, id, cargo, area, departamento, celular, 
               fecha_ingreso, correo, tipo_contrato, estado, telefono,
               " . rtrim($selectDocumentos, ", ") . "
        FROM tb_trabajadores 
        WHERE id_tipo = 1";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}