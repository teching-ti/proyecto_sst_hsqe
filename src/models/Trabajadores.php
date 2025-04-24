<?php

class Trabajadores{

    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function getTrabajadores($ids = []) {
        // Consulta ajustada para obtener solo el ingreso más reciente por trabajador
        // $query = "
        //     SELECT 
        //         t.activo, 
        //         t.apellidos, 
        //         t.nombres, 
        //         t.id, 
        //         t.cargo, 
        //         t.area, 
        //         t.departamento, 
        //         t.celular, 
        //         t.correo, 
        //         t.tipo_contrato, 
        //         t.telefono, 
        //         t.id_tipo, 
        //         t.modalidad AS modalidad_id, 
        //         t.sede AS sede_id, 
        //         m.nombre AS modalidad_nombre, 
        //         s.nombre AS sede_nombre, 
        //         mv.fecha AS fecha_ingreso
        //     FROM 
        //         tb_trabajadores t
        //     LEFT JOIN 
        //         tb_modalidad_trabajo m ON t.modalidad = m.id
        //     LEFT JOIN 
        //         tb_sede s ON t.sede = s.id
        //     LEFT JOIN (
        //         -- Subconsulta para obtener solo el ingreso más reciente
        //         SELECT 
        //             id_trabajador, 
        //             fecha
        //         FROM (
        //             SELECT 
        //                 id_trabajador, 
        //                 fecha,
        //                 ROW_NUMBER() OVER (PARTITION BY id_trabajador ORDER BY fecha DESC) AS rn
        //             FROM 
        //                 tb_movimiento_trabajadores
        //             WHERE 
        //                 tipo_movimiento = 'ingreso'
        //         ) AS sub
        //         WHERE 
        //             rn = 1
        //     ) mv ON t.id = mv.id_trabajador
        // ";

        $query = "
            SELECT 
                t.activo, 
                t.apellidos, 
                t.nombres, 
                t.id, 
                t.cargo, 
                t.area, 
                t.departamento, 
                t.celular, 
                t.correo, 
                t.tipo_contrato, 
                t.telefono, 
                t.id_tipo, 
                t.modalidad AS modalidad_id, 
                t.sede AS sede_id, 
                m.nombre AS modalidad_nombre, 
                s.nombre AS sede_nombre, 
                mv_ingreso.fecha AS fecha_ingreso,
                mv_cese.fecha AS fecha_cese
            FROM 
                tb_trabajadores t
            LEFT JOIN 
                tb_modalidad_trabajo m ON t.modalidad = m.id
            LEFT JOIN 
                tb_sede s ON t.sede = s.id
            LEFT JOIN (
                -- Subconsulta para obtener el ingreso más reciente
                SELECT 
                    id_trabajador, 
                    fecha
                FROM (
                    SELECT 
                        id_trabajador, 
                        fecha,
                        ROW_NUMBER() OVER (PARTITION BY id_trabajador ORDER BY fecha DESC) AS rn
                    FROM 
                        tb_movimiento_trabajadores
                    WHERE 
                        tipo_movimiento = 'ingreso'
                    ) AS sub
                WHERE 
                    rn = 1
            ) mv_ingreso ON t.id = mv_ingreso.id_trabajador
            LEFT JOIN (
                -- Subconsulta para obtener el cese más reciente
                SELECT 
                    id_trabajador, 
                    fecha
                FROM (
                    SELECT 
                        id_trabajador, 
                        fecha,
                        ROW_NUMBER() OVER (PARTITION BY id_trabajador ORDER BY fecha DESC) AS rn
                    FROM 
                        tb_movimiento_trabajadores
                    WHERE 
                        tipo_movimiento = 'cese'
                    ) AS sub
                WHERE 
                    rn = 1
            ) mv_cese ON t.id = mv_cese.id_trabajador
        ";
    
        // Cláusula WHERE en caso de que haya IDs
        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $query .= " WHERE t.id IN ($placeholders)";
        }
    
        // Agregar ORDER BY al final
        $query .= " ORDER BY t.apellidos";
    
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
    
        // Vincular parámetros si hay IDs
        if (!empty($ids)) {
            foreach ($ids as $k => $id) {
                $stmt->bindValue($k + 1, $id, PDO::PARAM_INT);
            }
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // mostrar modalidad de trabajo
    public function mostrarModalidad(){
        $catModel = new Categoriadocumentos($this->conn);
        $categorias = $catModel->getCategoriaDocumentos();
        return $categorias;
        //include 'src/views/documentos.php';
    }

    // mostrar modalidad de trabajo
    public function mostrarSede(){
        $catModel = new Categoriadocumentos($this->conn);
        $categorias = $catModel->getCategoriaDocumentos();
        return $categorias;
        //include 'src/views/documentos.php';
    }

    // insertar trabajadores en la base de datos
    public function setTrabajador($datos){
        try{
            $query = "INSERT INTO tb_trabajadores (id, apellidos, nombres, cargo, area, departamento, id_tipo, estado, celular, correo, tipo_contrato, activo, telefono, modalidad, sede) 
            VALUES 
            (:id, :apellidos, :nombres, :cargo, :area, :departamento, :id_tipo, :estado, :celular, :correo, :tipo_contrato, :activo, :telefono, :modalidad, :sede)";
            
            $smtm = $this->conn->prepare($query);
            // inserción de parámetros
            $smtm->bindParam('id', $datos['dni']);
            $smtm->bindParam('apellidos', $datos['apellidos']);
            $smtm->bindParam('nombres', $datos['nombres']);
            $smtm->bindParam('cargo', $datos['cargo']);
            $smtm->bindParam('area', $datos['area']);
            $smtm->bindParam('departamento', $datos['departamento']);
            $smtm->bindParam('id_tipo', $datos['id_tipo']);
            $smtm->bindParam('estado', $datos['estado']);
            $smtm->bindParam('celular', $datos['celular']);
            $smtm->bindParam('correo', $datos['correo']);
            $smtm->bindParam('tipo_contrato', $datos['tipo_contrato']);
            $smtm->bindParam('activo', $datos['activo']);
            $smtm->bindParam('telefono', $datos['telefono']);
            $smtm->bindParam('modalidad', $datos['modalidad']);
            $smtm->bindParam('sede', $datos['sede']);

            $smtm->execute();
            return true;
        }catch(PDOException $e){
            error_log('Error al insertar trabajador: ' . $e->getMessage());
            return false;
        }
    }

    // funcionalidad para crear registro también en la tabla de movimiento trabajadores
    public function registrarMovimientoLaboral($id_trabajador, $fecha, $tipo_movimiento, $motivo = null) {
        try {
            $query = "INSERT INTO tb_movimiento_trabajadores (id_trabajador, fecha, tipo_movimiento, motivo) 
                      VALUES (:id_trabajador, :fecha, :tipo_movimiento, :motivo)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_trabajador', $id_trabajador, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':tipo_movimiento', $tipo_movimiento);
            $stmt->bindParam(':motivo', $motivo);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log('Error al registrar movimiento laboral: ' . $e->getMessage());
            return false;
        }
    }

    public function getPersonalAdministrativoConDocumentos() {
        // Obtener los nombres de los documentos asociados a la categoría 1
        $docQuery = "SELECT id, nombre FROM tb_documentos WHERE cat_documento = 1";
        $stmt = $this->conn->prepare($docQuery);
        $stmt->execute();
        $documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Crear una consulta dinámica para seleccionar columnas de los documentos
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
                WHERE id_trabajador = t.id 
                AND id_documento = {$doc['id']}
                ORDER BY fecha_subida DESC
                LIMIT 1
                ) AS '{$doc['nombre']}', ";
        }
    
        // consulta principal incluyendo las columnas dinámicas de documentos y la fecha de ingreso
        $query = "
            SELECT 
                t.activo, 
                t.apellidos, 
                t.nombres, 
                t.id, 
                t.cargo, 
                t.area, 
                t.departamento, 
                t.celular, 
                t.correo, 
                t.tipo_contrato, 
                t.estado, 
                t.telefono, 
                s.nombre AS sede,
                m.nombre AS modalidad, 
                (
                    SELECT fecha 
                    FROM tb_movimiento_trabajadores mv 
                    WHERE mv.id_trabajador = t.id 
                    AND mv.tipo_movimiento = 'ingreso' 
                    ORDER BY mv.fecha DESC 
                    LIMIT 1
                ) AS fecha_ingreso,
                (
                SELECT fecha 
                FROM tb_movimiento_trabajadores mv 
                WHERE mv.id_trabajador = t.id 
                AND mv.tipo_movimiento = 'cese' 
                ORDER BY mv.fecha DESC 
                LIMIT 1
                ) AS fecha_cese,
                " . rtrim($selectDocumentos, ", ") . "
            FROM tb_trabajadores t
            LEFT JOIN tb_sede s ON t.sede = s.id
            LEFT JOIN tb_modalidad_trabajo m ON t.modalidad = m.id
            WHERE t.id_tipo = 1 
            ORDER BY t.apellidos
        ";
    
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
                WHERE id_trabajador = t.id 
                AND id_documento = {$doc['id']}
                ORDER BY fecha_subida DESC
                LIMIT 1
                ) AS '{$doc['nombre']}', ";
        }

        // consulta principal incluyendo las columnas dinámicas de documentos
        $query = "
            SELECT 
                t.activo, 
                t.apellidos, 
                t.nombres, 
                t.id, 
                t.cargo, 
                t.area, 
                t.departamento, 
                t.celular, 
                t.correo, 
                t.tipo_contrato, 
                t.estado, 
                t.telefono, 
                s.nombre AS sede,
                m.nombre AS modalidad,
                (
                    SELECT fecha 
                    FROM tb_movimiento_trabajadores mv 
                    WHERE mv.id_trabajador = t.id 
                    AND mv.tipo_movimiento = 'ingreso' 
                    ORDER BY mv.fecha DESC 
                    LIMIT 1
                ) AS fecha_ingreso,
                (
                SELECT fecha 
                FROM tb_movimiento_trabajadores mv 
                WHERE mv.id_trabajador = t.id 
                AND mv.tipo_movimiento = 'cese' 
                ORDER BY mv.fecha DESC 
                LIMIT 1
                ) AS fecha_cese,
                " . rtrim($selectDocumentos, ", ") . "
            FROM tb_trabajadores t
            LEFT JOIN tb_sede s ON t.sede = s.id
            LEFT JOIN tb_modalidad_trabajo m ON t.modalidad = m.id
            WHERE t.id_tipo = 2 ORDER BY t.apellidos
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function contarTrabajadoresPorTipo() {
        $query = "
            SELECT t.nombre, COUNT(tr.id) AS total
            FROM tb_tipotrabajadores t
            LEFT JOIN tb_trabajadores tr ON t.id = tr.id_tipo AND tr.activo = 1
            GROUP BY t.nombre;
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTrabajadoresPresencialesPorSede() {
        $query = "
            SELECT 
                s.nombre AS sede,
                COUNT(t.id) AS cantidad_trabajadores
            FROM 
                tb_trabajadores t
            JOIN 
                tb_modalidad_trabajo m ON t.modalidad = m.id
            JOIN 
                tb_sede s ON t.sede = s.id
            WHERE 
                t.activo = 1 AND m.nombre = 'Presencial'
            GROUP BY 
                s.nombre
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // modelo
    public function actualizarTrabajador($id, $activo, $id_tipo,$nombres, $apellidos, $cargo, $area, $departamento, $celular, $correo, $tipo_contrato, $modalidad, $sede){
        try {
            $sql_check = "SELECT COUNT(*) FROM tb_trabajadores WHERE id = :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':id', $id);
            $stmt_check->execute();
    
            if ($stmt_check->fetchColumn() == 0) {
                error_log("El trabajador con ID $id, $nombres, $apellidos, $cargo no existe.");
                return false;
            }

            $sql = "UPDATE tb_trabajadores SET apellidos = :apellidos, nombres = :nombres,  cargo = :cargo, area = :area, departamento = :departamento, id_tipo = :id_tipo, celular = :celular, correo =:correo, tipo_contrato = :tipo_contrato, activo = :activo, modalidad = :modalidad, sede= :sede WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':cargo', $cargo);
            $stmt->bindParam(':area', $area);
            $stmt->bindParam(':departamento', $departamento);
            $stmt->bindParam(':id_tipo', $id_tipo);
            $stmt->bindParam(':celular', $celular);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':tipo_contrato', $tipo_contrato);
            $stmt->bindParam(':activo', $activo);
            $stmt->bindParam(':modalidad', $modalidad);
            $stmt->bindParam(':sede', $sede);
            
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

    public function getTrabajadoresPorFecha($anio, $mes) {
        $query = "
            SELECT t.nombres, t.apellidos, t.cargo, t.area, t.departamento, m.fecha, m.tipo_movimiento, m.motivo 
            FROM tb_trabajadores t
            INNER JOIN tb_movimiento_trabajadores m ON t.id = m.id_trabajador
            WHERE YEAR(m.fecha) = :year
              AND MONTH(m.fecha) = :month
            ORDER BY m.fecha ASC";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':year', $anio, PDO::PARAM_INT);
        $stmt->bindParam(':month', $mes, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function setInactivoTrabajador($documento, $fechaCese, $tipoMovimiento, $motivoCese){
        try {
            $this->conn->beginTransaction();
            $sql_check = "SELECT activo FROM tb_trabajadores WHERE id = :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':id', $documento);
            $stmt_check->execute();
            $resultado = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if (!$resultado) {
                throw new Exception("El trabajador seleccionado no existe.");
            }
    
            if ($resultado['activo'] == 0) {
                throw new Exception("No se realizó esta acción, el trabajador seleccionado ya se encontraba cesado.");
            }

            $queryUpdate = "UPDATE tb_trabajadores SET activo = 0 WHERE id = :id";
            $stmtUpdate = $this->conn->prepare($queryUpdate);
            $stmtUpdate->bindParam(":id", $documento);
            $stmtUpdate->execute();

            $queryMovimiento = "INSERT INTO tb_movimiento_trabajadores (id_trabajador, fecha, tipo_movimiento, motivo)
                                    VALUES (:id_trabajador, :fecha, :tipo_movimiento, :motivo)";
            $stmtMovimiento = $this->conn->prepare($queryMovimiento);
            $stmtMovimiento->bindParam(":id_trabajador", $documento);
            $stmtMovimiento->bindParam(":fecha", $fechaCese);
            $stmtMovimiento->bindParam(":tipo_movimiento", $tipoMovimiento);
            $stmtMovimiento->bindParam(":motivo", $motivoCese);
            $stmtMovimiento->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function getIngresosUltimosMeses() {
        $query = "
            SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes_anio, COUNT(*) AS cantidad_ingresos FROM tb_movimiento_trabajadores WHERE tipo_movimiento = 'ingreso' AND fecha >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 5 MONTH), '%Y-%m-01') GROUP BY mes_anio ORDER BY mes_anio DESC LIMIT 6;
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>