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
                    <th>DNI</th>
                    <th>Apellido</th>
                    <th>Nombres</th>
                    <th>Cargo</th>
                    <th>Area</th>
                    <th>F.Ingreso</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>72978281</td>
                    <td>Villanueva</td>
                    <td>Alejandro</td>
                    <td>Programador Senior</td>
                    <td>Desarrollo</td>
                    <td>2024-09-11</td>
                </tr>
                <tr>
                    <td>72978456</td>
                    <td>Villanueva</td>
                    <td>Alejandro</td>
                    <td>Analista</td>
                    <td>Desarrollo</td>
                    <td>2024-09-11</td>
                </tr>
                <tr>
                    <td>1054968754268495120415</td>
                    <td>Villanueva</td>
                    <td>Alejandro</td>
                    <td>Documentador</td>
                    <td>Desarrollo</td>
                    <td>2024-09-11</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Villanueva</td>
                    <td>Alejandro</td>
                    <td>Gestor  </td>
                    <td>Desarrollo</td>
                    <td>2024-09-11</td>
                </tr>
            </tbody>
        </table>

        <button id="btn-agregar-personal">
            <i class="fa-solid fa-plus"></i>
        </button>
    </main>
    </div>
</body>
</html>