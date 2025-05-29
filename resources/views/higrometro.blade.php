<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoreo de Humedad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #4ecdc4;
            --secondary-color: #6ce0d7;
            --bg-color: #f8f9fa;
        }
        
        body {
            background-color: var(--bg-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .sensor-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }
        
        .sensor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border-bottom: none;
        }
        
        .current-value {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .unit {
            font-size: 1.5rem;
            color: #6c757d;
            vertical-align: super;
        }
        
        .status-badge {
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 500;
            display: inline-block;
        }
        
        .status-good {
            background-color: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
        }
        
        .status-warning {
            background-color: rgba(241, 196, 15, 0.2);
            color: #f1c40f;
        }
        
        .status-danger {
            background-color: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
        }
        
        .info-item {
            padding: 15px;
            border-radius: 10px;
            background-color: white;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .info-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .info-item i {
            font-size: 1.5rem;
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        .btn-power {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(78, 205, 196, 0.3);
        }
        
        .btn-power:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(78, 205, 196, 0.4);
        }
        
        .btn-power:active {
            transform: translateY(0);
        }
        
        .btn-power.off {
            background-color: #95a5a6;
            box-shadow: 0 5px 15px rgba(149, 165, 166, 0.3);
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .battery-level {
            height: 20px;
            background-color: #ecf0f1;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 5px;
        }
        
        .battery-fill {
            height: 100%;
            background: linear-gradient(90deg, #2ecc71, #f1c40f);
            border-radius: 10px;
            transition: width 0.5s;
        }
        
        .back-button {
            color: white;
            background-color: rgba(255,255,255,0.2);
            border: none;
            border-radius: 50px;
            padding: 8px 15px;
            transition: all 0.3s;
        }
        
        .back-button:hover {
            background-color: rgba(255,255,255,0.3);
            transform: translateX(-3px);
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('monitoreo') }}" class="back-button">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
                <h1 class="text-center mb-0"><i class="fas fa-tint me-3"></i> Monitoreo de Humedad</h1>
                <div style="width: 100px;"></div> <!-- Espacio para balancear -->
            </div>
            <p class="lead text-center mt-2">Visualización en tiempo real de los datos del sensor de humedad</p>
        </div>
    </div>
    
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="sensor-card">
                    <div class="card-header">
                        <i class="fas fa-tint me-2"></i> Humedad Actual
                    </div>
                    <div class="card-body text-center">
                        <div class="current-value">65<span class="unit">%</span></div>
                        <div class="status-badge status-good mt-2">
                            <i class="fas fa-check-circle me-1"></i> Normal
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">Actualizado: <span id="update-time">hace 1 min</span></small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="chart-container">
                    <canvas id="humidityChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="info-item d-flex align-items-center">
                    <i class="fas fa-battery-three-quarters"></i>
                    <div>
                        <h5 class="mb-1">Batería</h5>
                        <div class="d-flex justify-content-between">
                            <span>82%</span>
                            <span>Restante: ~15h</span>
                        </div>
                        <div class="battery-level">
                            <div class="battery-fill" style="width: 82%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="info-item d-flex align-items-center">
                    <i class="fas fa-wifi"></i>
                    <div>
                        <h5 class="mb-1">Conexión</h5>
                        <div class="d-flex justify-content-between">
                            <span>WiFi 2.4GHz</span>
                            <span class="status-badge status-good">Estable</span>
                        </div>
                        <small class="text-muted">Última sincronización: 12:47:05</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="info-item d-flex align-items-center">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <h5 class="mb-1">Estado del Sensor</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="status-badge status-good">Operativo</span>
                            <span class="text-muted">ID: HM-4721-89</span>
                        </div>
                        <small class="text-muted">Firmware v1.9.2</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="info-item d-flex align-items-center">
                    <i class="fas fa-power-off"></i>
                    <div class="w-100">
                        <h5 class="mb-3">Control del Sensor</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <button id="powerButton" class="btn-power">
                                <i class="fas fa-power-off me-2"></i> Encendido
                            </button>
                            <div>
                                <small class="text-muted">Tiempo activo: </small>
                                <strong>1d 8h 47m</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="sensor-card">
                    <div class="card-header">
                        <i class="fas fa-history me-2"></i> Historial de Humedad
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 250px;">
                            <canvas id="historyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gráfico de humedad en tiempo real
        const humidityCtx = document.getElementById('humidityChart').getContext('2d');
        const humidityChart = new Chart(humidityCtx, {
            type: 'line',
            data: {
                labels: Array.from({length: 20}, (_, i) => `${i * 5} min`),
                datasets: [{
                    label: 'Humedad (%)',
                    data: [62, 63, 63, 64, 64, 65, 65, 65, 64, 64, 65, 66, 66, 65, 65, 64, 64, 63, 63, 62],
                    borderColor: '#4ecdc4',
                    backgroundColor: 'rgba(78, 205, 196, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 50,
                        max: 80,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
        
        // Gráfico de historial
        const historyCtx = document.getElementById('historyChart').getContext('2d');
        const historyChart = new Chart(historyCtx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Humedad Promedio',
                    data: [68, 65, 70, 62, 66, 63, 64],
                    borderColor: '#4ecdc4',
                    backgroundColor: 'rgba(78, 205, 196, 0.1)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                }
            }
        });
        
        // Botón de encendido/apagado
        const powerButton = document.getElementById('powerButton');
        let isOn = true;
        
        powerButton.addEventListener('click', function() {
            isOn = !isOn;
            
            if (isOn) {
                this.innerHTML = '<i class="fas fa-power-off me-2"></i> Encendido';
                this.classList.remove('off');
                simulateDataUpdate();
                startDataUpdates();
            } else {
                this.innerHTML = '<i class="fas fa-power-off me-2"></i> Apagado';
                this.classList.add('off');
                clearInterval(updateInterval);
            }
        });
        
        // Simulación de actualización de datos
        function simulateDataUpdate() {
            const currentValueElement = document.querySelector('.current-value');
            const updateTimeElement = document.getElementById('update-time');
            const statusBadge = document.querySelector('.status-badge');
            const batteryFill = document.querySelector('.battery-fill');
            
            if (isOn) {
                // Generar nuevo valor de humedad
                const newHumidity = Math.floor(55 + Math.random() * 20);
                currentValueElement.innerHTML = `${newHumidity}<span class="unit">%</span>`;
                
                // Actualizar gráfico
                const newData = humidityChart.data.datasets[0].data.slice(1);
                newData.push(newHumidity);
                humidityChart.data.datasets[0].data = newData;
                humidityChart.update();
                
                // Actualizar tiempo
                const now = new Date();
                updateTimeElement.textContent = `hace ${now.getMinutes() % 2 === 0 ? '1' : '2'} min`;
                
                // Actualizar estado
                if (newHumidity > 75) {
                    statusBadge.className = 'status-badge status-warning';
                    statusBadge.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Alta';
                } else if (newHumidity < 45) {
                    statusBadge.className = 'status-badge status-danger';
                    statusBadge.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i> Baja';
                } else {
                    statusBadge.className = 'status-badge status-good';
                    statusBadge.innerHTML = '<i class="fas fa-check-circle me-1"></i> Normal';
                }
                
                // Reducir batería
                if (Math.random() > 0.7) {
                    const currentBattery = parseInt(batteryFill.style.width);
                    if (currentBattery > 5) {
                        batteryFill.style.width = `${currentBattery - 1}%`;
                    }
                }
            }
        }
        
        // Iniciar actualizaciones periódicas
        let updateInterval = setInterval(simulateDataUpdate, 5000);
    </script>
</body>
</html>