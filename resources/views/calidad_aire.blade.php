<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoreo de Calidad del Aire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #45aaf2;
            --secondary-color: #6bc1ff;
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
            box-shadow: 0 5px 15px rgba(69, 170, 242, 0.3);
        }
        
        .btn-power:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(69, 170, 242, 0.4);
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
        
        .aqi-scale {
            display: flex;
            height: 10px;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 10px;
        }
        
        .aqi-good { background-color: #2ecc71; flex: 1; }
        .aqi-moderate { background-color: #f1c40f; flex: 1; }
        .aqi-unhealthy-sensitive { background-color: #e67e22; flex: 1; }
        .aqi-unhealthy { background-color: #e74c3c; flex: 1; }
        .aqi-very-unhealthy { background-color: #9b59b6; flex: 1; }
        .aqi-hazardous { background-color: #7f8c8d; flex: 1; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('monitoreo') }}" class="back-button">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
                <h1 class="text-center mb-0"><i class="fas fa-wind me-3"></i> Monitoreo de Calidad del Aire</h1>
                <div style="width: 100px;"></div> <!-- Espacio para balancear -->
            </div>
            <p class="lead text-center mt-2">Visualización en tiempo real del índice de calidad del aire (AQI)</p>
        </div>
    </div>
    
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="sensor-card">
                    <div class="card-header">
                        <i class="fas fa-smog me-2"></i> Calidad del Aire
                    </div>
                    <div class="card-body text-center">
                        <div class="current-value">78<span class="unit">AQI</span></div>
                        <div class="status-badge status-warning mt-2">
                            <i class="fas fa-exclamation-triangle me-1"></i> Moderado
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">Actualizado: <span id="update-time">hace 3 min</span></small>
                        </div>
                        <div class="aqi-scale mt-3">
                            <div class="aqi-good" title="Buena (0-50)"></div>
                            <div class="aqi-moderate" title="Moderada (51-100)"></div>
                            <div class="aqi-unhealthy-sensitive" title="Dañina a grupos sensibles (101-150)"></div>
                            <div class="aqi-unhealthy" title="Dañina (151-200)"></div>
                            <div class="aqi-very-unhealthy" title="Muy dañina (201-300)"></div>
                            <div class="aqi-hazardous" title="Peligrosa (301-500)"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="chart-container">
                    <canvas id="aqiChart"></canvas>
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
                            <span>65%</span>
                            <span>Restante: ~8h</span>
                        </div>
                        <div class="battery-level">
                            <div class="battery-fill" style="width: 65%"></div>
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
                            <span>WiFi 5GHz</span>
                            <span class="status-badge status-good">Estable</span>
                        </div>
                        <small class="text-muted">Última sincronización: 12:48:37</small>
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
                            <span class="text-muted">ID: AQ-5824-76</span>
                        </div>
                        <small class="text-muted">Firmware v3.1.0</small>
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
                                <strong>3d 6h 12m</strong>
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
                        <i class="fas fa-history me-2"></i> Historial de Calidad del Aire
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
        // Gráfico de AQI en tiempo real
        const aqiCtx = document.getElementById('aqiChart').getContext('2d');
        const aqiChart = new Chart(aqiCtx, {
            type: 'line',
            data: {
                labels: Array.from({length: 20}, (_, i) => `${i * 5} min`),
                datasets: [{
                    label: 'Índice AQI',
                    data: [72, 75, 76, 78, 80, 82, 80, 78, 77, 76, 75, 74, 75, 76, 77, 78, 79, 80, 79, 78],
                    borderColor: '#45aaf2',
                    backgroundColor: 'rgba(69, 170, 242, 0.1)',
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
                        beginAtZero: true,
                        max: 150,
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
                    label: 'AQI Promedio',
                    data: [65, 70, 85, 92, 78, 82, 75],
                    borderColor: '#45aaf2',
                    backgroundColor: 'rgba(69, 170, 242, 0.1)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
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
                // Generar nuevo valor de AQI
                const newAqi = Math.floor(60 + Math.random() * 50);
                currentValueElement.innerHTML = `${newAqi}<span class="unit">AQI</span>`;
                
                // Actualizar gráfico
                const newData = aqiChart.data.datasets[0].data.slice(1);
                newData.push(newAqi);
                aqiChart.data.datasets[0].data = newData;
                aqiChart.update();
                
                // Actualizar tiempo
                const now = new Date();
                updateTimeElement.textContent = `hace ${now.getMinutes() % 2 === 0 ? '1' : '2'} min`;
                
                // Actualizar estado según AQI
                if (newAqi > 150) {
                    statusBadge.className = 'status-badge status-danger';
                    statusBadge.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i> Dañino';
                } else if (newAqi > 100) {
                    statusBadge.className = 'status-badge status-warning';
                    statusBadge.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Sensibles';
                } else if (newAqi > 50) {
                    statusBadge.className = 'status-badge status-warning';
                    statusBadge.innerHTML = '<i class="fas fa-info-circle me-1"></i> Moderado';
                } else {
                    statusBadge.className = 'status-badge status-good';
                    statusBadge.innerHTML = '<i class="fas fa-check-circle me-1"></i> Bueno';
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