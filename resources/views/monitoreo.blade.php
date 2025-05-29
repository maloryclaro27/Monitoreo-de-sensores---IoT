<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoreo de Sensores SabiOwl</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --card-termometro: #ff6b6b;
            --card-higrometro: #4ecdc4;
            --card-aire: #45aaf2;
            --card-uv: #a55eea;
            --card-barometro: #fd9644;
        }
        
        body {
            background-color: #f8f9fa;
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
            height: 100%;
        }
        
        .sensor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        .sensor-card .card-header {
            border-bottom: none;
            font-weight: 600;
            font-size: 1.2rem;
            color: white;
        }
        
        .sensor-card .card-body {
            padding: 1.5rem;
            background-color: white;
        }
        
        .sensor-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .sensor-value {
            font-size: 2rem;
            font-weight: 700;
            margin: 10px 0;
        }
        
        .sensor-unit {
            font-size: 1rem;
            color: #6c757d;
        }
        
        .btn-sensor {
            border: none;
            border-radius: 50px;
            font-weight: 500;
            padding: 8px 20px;
            transition: all 0.3s;
        }
        
        .btn-sensor:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }
        
        /* Colores específicos para cada tarjeta */
        .termometro-card .card-header { background-color: var(--card-termometro); }
        .termometro-card .btn-sensor { background-color: var(--card-termometro); color: white; }
        
        .higrometro-card .card-header { background-color: var(--card-higrometro); }
        .higrometro-card .btn-sensor { background-color: var(--card-higrometro); color: white; }
        
        .aire-card .card-header { background-color: var(--card-aire); }
        .aire-card .btn-sensor { background-color: var(--card-aire); color: white; }
        
        .uv-card .card-header { background-color: var(--card-uv); }
        .uv-card .btn-sensor { background-color: var(--card-uv); color: white; }
        
        .barometro-card .card-header { background-color: var(--card-barometro); }
        .barometro-card .btn-sensor { background-color: var(--card-barometro); color: white; }
        
        /* Animación de carga para los valores */
        @keyframes pulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }
        
        .loading {
            animation: pulse 1.5s infinite;
            background-color: #e9ecef;
            color: transparent;
            border-radius: 4px;
            display: inline-block;
            width: 80px;
            height: 36px;
        }
        
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        
        .status-good { background-color: #2ecc71; }
        .status-warning { background-color: #f39c12; }
        .status-danger { background-color: #e74c3c; }
    </style>
</head>
<body>
    <div class="header text-center">
        <div class="container">
            <h1><i class="fas fa-chart-line me-3"></i> Monitoreo de Sensores SabiOwl</h1>
            <p class="lead">Visualización en tiempo real de los datos de los sensores ambientales</p>
        </div>
    </div>
    
    <div class="container">
        <div class="row">
            <!-- Card Termómetro -->
            <div class="col-md-4 col-lg-4">
                <div class="card sensor-card termometro-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-temperature-high me-2"></i> Termómetro</span>
                        <span class="status-indicator status-good"></span>
                    </div>
                    <div class="card-body text-center">
                        <div class="sensor-icon text-danger">
                            <i class="fas fa-thermometer-half"></i>
                        </div>
                        <div class="sensor-value">23.5<span class="sensor-unit">°C</span></div>
                        <p class="text-muted">Temperatura ambiente</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">Actualizado: <span class="update-time">hace 2 min</span></small>
                            <a href="{{ route('termometro') }}" class="btn btn-sensor"> Ver detalles <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card Higrómetro -->
            <div class="col-md-4 col-lg-4">
                <div class="card sensor-card higrometro-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-tint me-2"></i> Higrómetro</span>
                        <span class="status-indicator status-good"></span>
                    </div>
                    <div class="card-body text-center">
                        <div class="sensor-icon text-info">
                            <i class="fas fa-water"></i>
                        </div>
                        <div class="sensor-value">65<span class="sensor-unit">%</span></div>
                        <p class="text-muted">Humedad relativa</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">Actualizado: <span class="update-time">hace 1 min</span></small>
                            <a href="{{ route('higrometro') }}" class="btn btn-sensor">
                                Ver detalles <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card Calidad del Aire -->
            <div class="col-md-4 col-lg-4">
                <div class="card sensor-card aire-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-wind me-2"></i> Calidad del Aire</span>
                        <span class="status-indicator status-warning"></span>
                    </div>
                    <div class="card-body text-center">
                        <div class="sensor-icon text-primary">
                            <i class="fas fa-smog"></i>
                        </div>
                        <div class="sensor-value">78<span class="sensor-unit">AQI</span></div>
                        <p class="text-muted">Índice de calidad del aire</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">Actualizado: <span class="update-time">hace 3 min</span></small>
                            <a href="{{ route('calidad_aire') }}" class="btn btn-sensor">
                                Ver detalles <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card UV -->
            <div class="col-md-4 col-lg-4">
                <div class="card sensor-card uv-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-sun me-2"></i> Radiación UV</span>
                        <span class="status-indicator status-danger"></span>
                    </div>
                    <div class="card-body text-center">
                        <div class="sensor-icon text-warning">
                            <i class="fas fa-sun"></i>
                        </div>
                        <div class="sensor-value">8.2<span class="sensor-unit">UVI</span></div>
                        <p class="text-muted">Índice ultravioleta</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">Actualizado: <span class="update-time">hace 5 min</span></small>
                            <a href="sensor-uv.html" class="btn btn-sensor">
                                Ver detalles <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card Barómetro -->
            <div class="col-md-4 col-lg-4">
                <div class="card sensor-card barometro-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-tachometer-alt me-2"></i> Barómetro</span>
                        <span class="status-indicator status-good"></span>
                    </div>
                    <div class="card-body text-center">
                        <div class="sensor-icon text-success">
                            <i class="fas fa-compress-arrows-alt"></i>
                        </div>
                        <div class="sensor-value">1013<span class="sensor-unit">hPa</span></div>
                        <p class="text-muted">Presión atmosférica</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">Actualizado: <span class="update-time">hace 2 min</span></small>
                            <a href="sensor-barometro.html" class="btn btn-sensor">
                                Ver detalles <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efecto hover mejorado para las cards
        document.querySelectorAll('.sensor-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transition = 'all 0.3s ease';
            });
        });
        
        // Simulación de actualización de datos (en una aplicación real, esto vendría de una API)
        function simulateDataUpdate() {
            const cards = document.querySelectorAll('.sensor-card');
            
            cards.forEach(card => {
                const valueElement = card.querySelector('.sensor-value');
                const timeElement = card.querySelector('.update-time');
                
                if(valueElement && timeElement) {
                    // Guardar el valor original para la animación
                    const originalValue = valueElement.textContent;
                    
                    // Mostrar estado de carga
                    valueElement.innerHTML = '<span class="loading"></span>';
                    
                    // Simular demora de red
                    setTimeout(() => {
                        // Generar un pequeño cambio aleatorio en el valor
                        let newValue;
                        if (card.classList.contains('termometro-card')) {
                            newValue = (22.5 + Math.random() * 2).toFixed(1);
                            valueElement.innerHTML = `${newValue}<span class="sensor-unit">°C</span>`;
                        } else if (card.classList.contains('higrometro-card')) {
                            newValue = Math.floor(60 + Math.random() * 10);
                            valueElement.innerHTML = `${newValue}<span class="sensor-unit">%</span>`;
                        } else if (card.classList.contains('aire-card')) {
                            newValue = Math.floor(70 + Math.random() * 20);
                            valueElement.innerHTML = `${newValue}<span class="sensor-unit">AQI</span>`;
                        } else if (card.classList.contains('uv-card')) {
                            newValue = (7 + Math.random() * 3).toFixed(1);
                            valueElement.innerHTML = `${newValue}<span class="sensor-unit">UVI</span>`;
                        } else if (card.classList.contains('barometro-card')) {
                            newValue = Math.floor(1010 + Math.random() * 8);
                            valueElement.innerHTML = `${newValue}<span class="sensor-unit">hPa</span>`;
                        }
                        
                        // Actualizar tiempo
                        timeElement.textContent = 'hace unos segundos';
                        
                        // Actualizar indicador de estado
                        const indicator = card.querySelector('.status-indicator');
                        if (indicator) {
                            // Simular cambio de estado aleatorio
                            const status = Math.random();
                            indicator.className = 'status-indicator ' + 
                                (status > 0.8 ? 'status-danger' : status > 0.5 ? 'status-warning' : 'status-good');
                        }
                        
                        // Efecto visual de actualización
                        valueElement.style.transform = 'scale(1.1)';
                        setTimeout(() => {
                            valueElement.style.transform = 'scale(1)';
                        }, 300);
                        
                    }, 1000 + Math.random() * 1000);
                }
            });
        }
        
        // Actualizar datos cada 30 segundos
        setInterval(simulateDataUpdate, 30000);
        
        // También actualizar al hacer clic en cualquier card
        document.querySelectorAll('.sensor-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('a')) { // Evitar si se hace clic en un enlace
                    simulateDataUpdate();
                }
            });
        });
    </script>
</body>
</html>