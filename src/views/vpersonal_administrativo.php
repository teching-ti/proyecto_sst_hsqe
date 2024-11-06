<?php 
$hoja_de_estilos = "p_administrativo.css?v=".time();
$titulo = "Personal Administrativo";
include "base.php";
?>
    <main>
        <h1>Listado de Personal Administrativo</h1>
        <table class="tb-principal">
        <thead>
            <tr>
                <th>Item</th>
                <th>Activo</th>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th>DNI</th>
                <th>Cargo</th>
                <th>Área</th>
                <th>Departamento</th>
                <th>Celular</th>
                <th>Fecha de Ingreso</th>
                <th>Correo</th>
                <th>Tipo de Contrato</th>
                <th>Telefono</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $contador = 1;
                foreach ($personalAdministrativo as $trabajador): ?>
                <tr>
                    <td><?php echo $contador++; ?></td>
                    <td><?php echo htmlspecialchars($trabajador['activo'] ? 'Sí' : 'No'); ?></td>
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
                    <td><?php echo htmlspecialchars($trabajador['estado']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

        <button id="btn-agregar-personal">
            <i class="fa-solid fa-plus"></i>
        </button>
    </main>
    </div>
</body>
</html>