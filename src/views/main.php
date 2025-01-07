<?php 
$hoja_de_estilos = "inicio.css?v=".time();
$titulo = "Inicio";
include "base.php";
require_once "src/controllers/TrabajadoresController.php";
?>
            <?php if($usuario_logueado):?>
                <p class="usuario_presente"><?= $_SESSION['nombres']; ?> <?= $_SESSION['apellidos']; ?></p>
            <?php else:?>
                <p>No hay sesiones activas</p>
            <?php endif; ?>

            <div class="contenedor-graficos">
                <div class="grafico1">
                    <canvas id="grafico-tipo-trabajadores"></canvas>
                </div>
                <div class="grafico2">
                    <canvas id="grafico-presencial-sede" width="800" height="600"></canvas>
                </div>
            </div>
            <div>
                <span>Inicio</span>
            </div>
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

            // crear gráfico
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels, // Etiquetas dinámicas
                    datasets: [{
                        label: 'Distribución del Personal',
                        data: valores, // Valores dinámicos
                        backgroundColor: [
                            'rgba(54, 117, 235, 0.6)', 
                            'rgba(175, 189, 235, 0.6)' 
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgb(207, 194, 197)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    
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


    fetch('index.php?page=getDistribucionPresencialPorSede')
        .then(response => response.json())
        .then(datos => {
            const labels = datos.map(item => item.sede);
            const data = datos.map(item => item.cantidad_trabajadores);

            const ctx = document.getElementById('grafico-presencial-sede').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Trabajadores Presenciales',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }],
                    barPercentage: 0.4,
                    categoryPercentage: 0.2
                },
                options: {
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
                        title: {
                            display: true,
                            text: 'Trabajadores Por Sede',
                            color: "rgb(255, 255, 255)",
                            font: {
                                size: 16,
                            }
                        },
                    },
                }
            });
        })
        .catch(error => console.error('Error al cargar los datos:', error));
</script>
</body>
</html>