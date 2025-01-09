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

            <div class='contenedor-graficos'>
                <div class='grafico'>
                    <canvas id='grafico-tipo-trabajadores'></canvas>
                </div>
                <div class='grafico'>
                    <canvas id='grafico-presencial-sede' width='800' height='600'></canvas>
                </div>
                <div class='grafico'>
                    <canvas id='grafico-linea-ingresos' width='800' height='600'></canvas>
                </div>
            </div>
             
        </aside>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const grafico1 = document.getElementById('grafico-tipo-trabajadores');
    if(grafico1){
    const ctx1 = grafico1.getContext('2d');

    fetch('index.php?page=getDistribucionTrabajadores')
        .then(response => response.json())
        .then(data => {
            const labels = Object.keys(data);
            //const valores = Object.values(data);
            const valores = Object.values(data).map(val => parseFloat(val));

            const total = valores.reduce((sum, val) => sum + val, 0);

            new Chart(ctx1, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Distribución del Personal',
                        data: valores,
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
                            text: "Distribución de Trabajadores",
                            color: "rgb(255, 255, 255)",
                            font: {
                                size: 16,
                            }
                        },
                        subtitle: {
                            display: true,
                            text: `Total: ${total.toLocaleString()}`,
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
    }

    const grafico2 = document.getElementById('grafico-presencial-sede')
    if(grafico2){
    const ctx2 = grafico2.getContext('2d');
    fetch('index.php?page=getDistribucionPresencialPorSede')
    .then(response => response.json())
    .then(datos => {
        const labels = datos.map(item => item.sede);
        const data = datos.map(item => item.cantidad_trabajadores);

        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sedes',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    barPercentage: 0.3,
                    categoryPercentage: 0.8
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
                    title: {
                        display: true,
                        text: 'Trabajadores con Modalidad Presencial',
                        color: "rgb(255, 255, 255)",
                        font: {
                            size: 16,
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.2)',
                            lineWidth: 1
                        },
                        ticks: {
                            color: 'rgb(255, 255, 255)'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.2)',
                            lineWidth: 1
                        },
                        ticks: {
                            color: 'rgb(255, 255, 255)'
                        }
                    }
                }
            }
        });
    })
    .catch(error => console.error('Error al cargar los datos:', error));
    };
    
    const grafico3 = document.getElementById('grafico-linea-ingresos');
    if(grafico3){
    const ctx3 = grafico3.getContext('2d');
    fetch('index.php?page=getUltimosIngresos')
        .then(response => response.json())
        .then(datos => {
            console.log(datos);
            let datosFiltrados = datos.reverse();
            const labels = datos.map(item => item.mes_anio);
            const data = datos.map(item => item.cantidad_ingresos);

            new Chart(ctx3, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Cantidad de ingresos',
                        data: data,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        pointRadius: 4
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
                        title: {
                            display: true,
                            text: 'Ingresos Mensuales de Trabajadores',
                            color: "rgb(255, 255, 255)",
                            font: {
                                size: 16,
                            }
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.2)',
                                lineWidth: 1
                            },
                            ticks: {
                                color: 'rgb(255, 255, 255)'
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.2)',
                                lineWidth: 1
                            },
                            ticks: {
                                color: 'rgb(255, 255, 255)'
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error al cargar los datos:', error));
    };
    });
</script>
</body>
</html>