<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoreo de Temperatura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- 1) Chart.js antes de usarlo -->
    <script type="module">
      import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm';
      window.supabase = createClient(
        '{{ $supabaseUrl }}',
        '{{ $supabaseKey }}'
      );
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-color: #ff6b6b;
            --secondary-color: #ff8e8e;
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
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }
        
        .btn-power:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 107, 107, 0.4);
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
                <h1 class="text-center mb-0"><i class="fas fa-temperature-high me-3"></i> Monitoreo de Temperatura</h1>
                <div style="width: 100px;"></div>
            </div>
            <p class="lead text-center mt-2">Visualización en tiempo real de los datos del sensor de temperatura</p>
        </div>
    </div>
    
    <div class="container">
        <div class="row mb-4">
            <!-- Card indicador -->
            <div class="col-md-4">
                <div class="sensor-card">
                    <div class="card-header">
                        <i class="fas fa-thermometer-half me-2"></i> Temperatura Actual
                    </div>
                    <div class="card-body text-center">
                        <div id="currentValue" class="current-value">--<span class="unit">°C</span></div>
                        <div id="currentStatus" class="status-badge status-good mt-2">
                            <i class="fas fa-check-circle me-1"></i> Normal
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">Última lectura: <span id="currentTime">--:--:--</span></small>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Gráfico dinámico -->
            <div class="col-md-8">
                <div class="chart-container">
                    <canvas id="tempChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Resto de info-items (batería, conexión, estado, control) -->
        <div class="row">
            <div class="col-md-6">
                <div class="info-item d-flex align-items-center">
                    <i class="fas fa-battery-three-quarters"></i>
                    <div>
                        <h5 class="mb-1">Batería</h5>
                        <div class="d-flex justify-content-between">
                            <span id="batteryText">--%</span>
                            <span>Estimada: --h</span>
                        </div>
                        <div class="battery-level">
                            <div id="batteryFill" class="battery-fill" style="width: 0%"></div>
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
                            <span id="signalText">-- dBm</span>
                            <span id="signalStatus" class="status-badge status-good">--</span>
                        </div>
                        <small class="text-muted">Última sincronización: <span id="syncTime">--:--:--</span></small>
                    </div>
                </div>
            </div>
            <!-- ... otros info-items ... -->
        </div>
    </div>

    <!-- 4) Script de inicialización, carga y realtime -->
    <script type="module">
    const supabase = window.supabase;
    const ctx = document.getElementById('tempChart').getContext('2d');
    const tempChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Temperatura (°C)',
                data: [],
                tension: 0.3,
                fill: false
            }]
        },
        options: {
            scales: {
                x: { title: { display: true, text: 'Hora' } },
                y: { title: { display: true, text: 'Temperatura (°C)' }, beginAtZero: false }
            }
        }
    });

  // 1) Función de formateo de hora en Bogotá
    function formatTime(ts) {
        return new Date(ts + 'Z')
        .toLocaleTimeString('es-CO', {
            hour12: false,
            timeZone: 'America/Bogota'
        });
    }

  // 2) Carga inicial de últimos 50 datos
    async function cargarDatosIniciales() {
        const { data, error } = await supabase
        .from('termometro')
        .select('temperatura_valor, bateria_porcentaje, senal_red_dbm, timestamp')
        .order('timestamp', { ascending: true })
        .limit(50);

        if (error) return console.error('[init] error:', error);

        data.forEach(r => {
            const t    = parseFloat(r.temperatura_valor).toFixed(1);
            const time = formatTime(r.timestamp);
            const bat  = parseFloat(r.bateria_porcentaje).toFixed(1);
            const sig  = r.senal_red_dbm;

      // Actualizar indicadores
            document.getElementById('currentValue').innerHTML    = `${t}<span class="unit">°C</span>`;
            document.getElementById('currentTime').textContent   = time;
            document.getElementById('syncTime').textContent      = time;
            document.getElementById('batteryText').textContent   = `${bat}%`;
            document.getElementById('batteryFill').style.width   = `${bat}%`;
            document.getElementById('signalText').textContent    = `${sig} dBm`;
            document.getElementById('signalStatus').textContent  = sig < -80 ? 'Débil' : 'Estable';

      // Agregar punto al gráfico
            tempChart.data.labels.push(time);
            tempChart.data.datasets[0].data.push(parseFloat(t));
        });
        
        tempChart.update();
    }

  // 3) Suscripción en tiempo real
    async function suscribirseRealtime() {
        const channel = supabase
        .channel('realtime-termometro')
        .on('postgres_changes',
        { event: 'INSERT', schema: 'public', table: 'termometro' },
        ({ new: r }) => {
            console.log('[realtime] payload recibido:', r);

            const t    = parseFloat(r.temperatura_valor).toFixed(1);
            const time = formatTime(r.timestamp);
            const bat  = parseFloat(r.bateria_porcentaje).toFixed(1);
            const sig  = r.senal_red_dbm;

            // Actualizar indicadores
            document.getElementById('currentValue').innerHTML    = `${t}<span class="unit">°C</span>`;
            document.getElementById('currentTime').textContent   = time;
            document.getElementById('syncTime').textContent      = time;
            document.getElementById('batteryText').textContent   = `${bat}%`;
            document.getElementById('batteryFill').style.width   = `${bat}%`;
            document.getElementById('signalText').textContent    = `${sig} dBm`;
            document.getElementById('signalStatus').textContent  = sig < -80 ? 'Débil' : 'Estable';

            // Actualizar gráfico
            tempChart.data.labels.push(time);
            tempChart.data.datasets[0].data.push(parseFloat(t));
            if (tempChart.data.labels.length > 50) {
              tempChart.data.labels.shift();
              tempChart.data.datasets[0].data.shift();
            }
            tempChart.update();
        }
    );

    console.log('[realtime] intentando suscribir…');
    const { error } = await channel.subscribe();
    if (error) {
      console.error('[realtime] error al suscribir:', error);
    } else {
      console.log('[realtime] suscripción exitosa');
    }
  }

  // 4) Inicializar todo
  (async () => {
    await cargarDatosIniciales();
    await suscribirseRealtime();
  })();
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>