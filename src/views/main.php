<?php 
$hoja_de_estilos = "inicio.css?v=".time();
$titulo = "Inicio";
include "base.php";
require_once "src/controllers/TrabajadoresController.php";
?>
        <aside id="principal">
            <?php if($usuario_logueado):?>
                <p class="usuario_presente"><?= $_SESSION['nombres']; ?> <?= $_SESSION['apellidos']; ?></p>
            <?php else:?>
                <p>No hay sesiones activas</p>
            <?php endif; ?>

            <!-- <div class="grafico1">
                <canvas id="grafico-tipo-trabajadores" class="grafico-tipo-trabajadores"></canvas>
            </div> -->
            <!-- <div>
                <span>Inicio</span>
            </div> -->
        </aside>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('grafico-tipo-trabajadores').getContext('2d');

    // datos del servidor
    fetch('index.php?page=getDistribucionTrabajadores')
        .then(response => response.json())
        .then(data => {
            const labels = Object.keys(data);
            const valores = Object.values(data);

            const total = valores.reduce((sum, val) => sum + val, 0);

            // Crear gráfico
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels, // Etiquetas dinámicas
                    datasets: [{
                        label: 'Distribución del Personal',
                        data: valores, // Valores dinámicos
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.6)', 
                            'rgba(255, 99, 132, 0.6)' 
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: 'rgb(255, 255, 255)',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (tooltipItem) {
                                    const label = tooltipItem.label || '';
                                    const value = tooltipItem.raw;
                                    const percentage = ((value / total) * 100).toFixed(2);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: "Trabajadores de TECHING",
                            color: "rgb(255, 255, 255)",
                            font: {
                                size: 16,
                            }
                        },
                        subtitle: {
                            display: true,
                            text: `Total: ${total}`,
                            color: "rgb(244, 244, 244)",
                            font: {
                                size: 14,
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error al cargar los datos:', error);
        });
    });
</script>
</body>
</html>