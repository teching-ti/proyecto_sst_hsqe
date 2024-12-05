<?php

class Trabajadores{

    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function getTrabajadores($ids = []) {
        // realiza consulta a base de datos
        $query = "SELECT activo, apellidos, nombres, id, cargo, area, departamento, celular, fecha_ingreso, correo, tipo_contrato, telefono, id_tipo FROM tb_trabajadores";
        
        // clásula where en caso hayan id
        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $query .= " WHERE id IN ($placeholders)";
        }

        // agregar order by al final
        $query .= " ORDER BY apellidos";
    
        // se prepara la consulta
        $stmt = $this->conn->prepare($query);
        
        // se vinculan los parámetros siempre y cuando hayan IDS presentes en la consulta
        if (!empty($ids)) {
            foreach ($ids as $k => $id) {
                $stmt->bindValue($k + 1, $id, PDO::PARAM_INT);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // insertar trabajadores en la base de datos
    public function setTrabajador($datos){
        try{
            $query = "INSERT INTO tb_trabajadores (id, apellidos, nombres, cargo, area, departamento, fecha_ingreso, id_tipo, estado, celular, correo, tipo_contrato, activo, telefono) 
            VALUES 
            (:id, :apellidos, :nombres, :cargo, :area, :departamento, :fecha_ingreso, :id_tipo, :estado, :celular, :correo, :tipo_contrato, :activo, :telefono)";
            
            $smtm = $this->conn->prepare($query);
            // inserción de parámetros
            $smtm->bindParam('id', $datos['dni']);
            $smtm->bindParam('apellidos', $datos['apellidos']);
            $smtm->bindParam('nombres', $datos['nombres']);
            $smtm->bindParam('cargo', $datos['cargo']);
            $smtm->bindParam('area', $datos['area']);
            $smtm->bindParam('departamento', $datos['departamento']);
            $smtm->bindParam('fecha_ingreso', $datos['fecha_ingreso']);
            $smtm->bindParam('id_tipo', $datos['id_tipo']);
            $smtm->bindParam('estado', $datos['estado']);
            $smtm->bindParam('celular', $datos['celular']);
            $smtm->bindParam('correo', $datos['correo']);
            $smtm->bindParam('tipo_contrato', $datos['tipo_contrato']);
            $smtm->bindParam('activo', $datos['activo']);
            $smtm->bindParam('telefono', $datos['telefono']);

            $smtm->execute();
            return true;
        }catch(PDOException $e){
            error_log('Error al insertar trabajador: ' . $e->getMessage());
            return false;
        }
    }

    // public function getPersonalAdministrativo(){
    //     $query = "SELECT activo, apellidos, nombres, id, cargo, area, departamento, celular, fecha_ingreso, correo, tipo_contrato, estado, telefono FROM tb_trabajadores WHERE id_tipo = 1";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    // función para obtener al personal administrativo
    public function getPersonalAdministrativoConDocumentos() {
        // se obtienen los nombres de los documentos asociados a la categoría 1
        $docQuery = "SELECT id, nombre FROM tb_documentos WHERE cat_documento = 1";
        $stmt = $this->conn->prepare($docQuery);
        $stmt->execute();
        $documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // se crea una consulta dinámica para seleccionar columnas de los documentos
        $selectDocumentos = "";
        foreach ($documentos as $doc) {
            $selectDocumentos .= "
                (SELECT JSON_OBJECT(
                    'archivo', archivo_eval,
                    'nombre_archivo', nombre_archivo,
                    'fecha', fecha_subida,
                    'doc_id', id_documento
                ) 
                FROM tb_doctrabajadores 
                WHERE id_trabajador = tb_trabajadores.id 
                  AND id_documento = {$doc['id']}
                ORDER BY fecha_subida DESC
                LIMIT 1
                ) AS '{$doc['nombre']}', ";
        }

        // consulta principal incluyendo las columnas dinámicas de documentos
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

    // función para obtener al personal operativo
    public function getPersonalOperativoConDocumentos(){
        $docQuery = "SELECT id, nombre FROM tb_documentos WHERE cat_documento IN (1, 2)";
        $stmt = $this->conn->prepare($docQuery);
        $stmt->execute();
        $documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // se crea una consulta dinámica para seleccionar columnas de los documentos
        $selectDocumentos = "";
        foreach ($documentos as $doc) {
            $selectDocumentos .= "
                (SELECT JSON_OBJECT(
                    'archivo', archivo_eval,
                    'nombre_archivo', nombre_archivo,
                    'fecha', fecha_subida,
                    'doc_id', id_documento
                ) 
                FROM tb_doctrabajadores 
                WHERE id_trabajador = tb_trabajadores.id 
                  AND id_documento = {$doc['id']}
                ORDER BY fecha_subida DESC
                LIMIT 1
                ) AS '{$doc['nombre']}', ";
        }

        // consulta principal incluyendo las columnas dinámicas de documentos
        $query = "
            SELECT activo, apellidos, nombres, id, cargo, area, departamento, celular, 
                   fecha_ingreso, correo, tipo_contrato, estado, telefono,
                   " . rtrim($selectDocumentos, ", ") . "
            FROM tb_trabajadores WHERE id_tipo = 2";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function contarTrabajadoresPorTipo() {
        $query = "
            SELECT t.nombre, COUNT(tr.id) AS total
            FROM tb_tipotrabajadores t
            LEFT JOIN tb_trabajadores tr ON t.id = tr.id_tipo
            GROUP BY t.nombre
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve un arreglo con los resultados
    }

    // modelo
    public function actualizarTrabajador($id, $activo, $id_tipo,$nombres, $apellidos, $cargo, $area, $departamento, $celular, $fecha_ingreso, $correo, $tipo_contrato, $telefono){
        try {
            $sql_check = "SELECT COUNT(*) FROM tb_trabajadores WHERE id = :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':id', $id);
            $stmt_check->execute();
    
            if ($stmt_check->fetchColumn() == 0) {
                error_log("El trabajador con ID $id, $nombres, $apellidos, $cargo no existe.");
                return false;
            }

            $sql = "UPDATE tb_trabajadores SET apellidos = :apellidos, nombres = :nombres,  cargo = :cargo, area = :area, departamento = :departamento, fecha_ingreso = :fecha_ingreso, id_tipo = :id_tipo, celular = :celular, correo =:correo, tipo_contrato = :tipo_contrato, activo = :activo, telefono = :telefono WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':cargo', $cargo);
            $stmt->bindParam(':area', $area);
            $stmt->bindParam(':departamento', $departamento);
            $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);
            $stmt->bindParam(':id_tipo', $id_tipo);
            $stmt->bindParam(':celular', $celular);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':tipo_contrato', $tipo_contrato);
            $stmt->bindParam(':activo', $activo);
            $stmt->bindParam(':telefono', $telefono);
            
            // se tendrá que eliminar ese error log
            return $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                error_log("No se actualizó ninguna fila. ID: $id");
                return false;
            }

        } catch (PDOException $e) {
            error_log("Error al actualizar trabajador: " . $e->getMessage());
            return false;
        }
    }
}
?>