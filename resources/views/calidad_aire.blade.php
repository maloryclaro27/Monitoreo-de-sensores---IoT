{{-- resources/views/calidad_aire.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Monitoreo de Calidad del Aire</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Supabase JS (ESM) + Chart.js -->
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
      --primary-color: #45aaf2;
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
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      margin-bottom: 25px;
      transition: transform .3s, box-shadow .3s;
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
      vertical-align: super;
      color: #6c757d;
    }
    .status-badge {
      padding: 8px 15px;
      border-radius: 50px;
      font-weight: 500;
      display: inline-block;
    }
    .status-good { background-color: rgba(46,204,113,0.2); color:#2ecc71 }
    .status-warning { background-color: rgba(241,196,15,0.2); color:#f1c40f }
    .status-danger { background-color: rgba(231,76,60,0.2); color:#e74c3c }
    .info-item {
      padding: 15px;
      border-radius: 10px;
      background: white;
      margin-bottom: 15px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
      transition: transform .3s, box-shadow .3s;
    }
    .info-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .info-item i { font-size:1.5rem; margin-right:10px; color:var(--primary-color) }
    .btn-power {
      background-color: var(--primary-color);
      color: white;
      border:none;
      border-radius:50px;
      padding:10px 25px;
      font-weight:600;
      box-shadow:0 5px 15px rgba(69,170,242,0.3);
      transition:transform .3s, box-shadow .3s;
    }
    .btn-power.off { background-color:#95a5a6; box-shadow:0 5px 15px rgba(149,165,166,0.3); }
    .btn-power:hover { transform: translateY(-3px); box-shadow:0 8px 20px rgba(69,170,242,0.4); }
    .chart-container {
      position: relative;
      height:300px;
      width:100%;
      background:white;
      border-radius:15px;
      padding:20px;
      box-shadow:0 5px 15px rgba(0,0,0,0.1);
    }
    .battery-level {
      height:20px;
      background:#ecf0f1;
      border-radius:10px;
      overflow:hidden;
      margin-top:5px;
    }
    .battery-fill {
      height:100%;
      background: linear-gradient(90deg,#2ecc71,#f1c40f);
      border-radius:10px;
      transition:width .5s;
    }
    .back-button {
      color:white;
      background: rgba(255,255,255,0.2);
      border:none;
      border-radius:50px;
      padding:8px 15px;
      transition:background .3s,transform .3s;
    }
    .back-button:hover { background: rgba(255,255,255,0.3); transform:translateX(-3px); }
  </style>
</head>
<body>
  <div class="header">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('monitoreo') }}" class="back-button">
          <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
        <h1 class="text-center mb-0"><i class="fas fa-smog me-3"></i> Monitoreo de Calidad del Aire</h1>
        <div style="width:100px;"></div>
      </div>
      <p class="lead text-center mt-2">Visualización en tiempo real del índice de calidad del aire (AQI)</p>
    </div>
  </div>

  <div class="container">
    <div class="row mb-4">
      <!-- Indicador principal -->
      <div class="col-md-4">
        <div class="sensor-card">
          <div class="card-header">
            <i class="fas fa-smog me-2"></i> Calidad del Aire
          </div>
          <div class="card-body text-center">
            <div id="currentValue" class="current-value">--<span class="unit">AQI</span></div>
            <div id="currentStatus" class="status-badge status-good mt-2">
              <i class="fas fa-check-circle me-1"></i> Bueno
            </div>
            <div class="mt-3">
              <small class="text-muted">Última lectura: <span id="currentTime">--:--:--</span></small>
            </div>
          </div>
        </div>
      </div>
      <!-- Gráfico -->
      <div class="col-md-8">
        <div class="chart-container">
          <canvas id="aqiChart"></canvas>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Batería -->
      <div class="col-md-6">
        <div class="info-item d-flex align-items-center">
          <i class="fas fa-battery-three-quarters"></i>
          <div>
            <h5 class="mb-1">Batería</h5>
            <div class="d-flex justify-content-between">
              <span id="batteryText">--%</span>
              <span>Estimado: --h</span>
            </div>
            <div class="battery-level">
              <div id="batteryFill" class="battery-fill" style="width:0%"></div>
            </div>
          </div>
        </div>
      </div>
      <!-- Conexión -->
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
    </div>

    <!-- Botón invertir estado -->
    <div class="row mb-4">
      <div class="col text-center">
        <button id="toggleEstadoButton" class="btn btn-warning">
          <i class="fas fa-exchange-alt me-2"></i>On/Off
        </button>
      </div>
    </div>
  </div>

  <script type="module">
    const supabase = window.supabase;
    const ctx = document.getElementById('aqiChart').getContext('2d');
    const aqiChart = new Chart(ctx, {
      type: 'line',
      data: { labels: [], datasets:[{ label:'AQI', data:[], tension:0.3, fill:false }] },
      options:{ scales:{ x:{ title:{display:true,text:'Hora'} }, y:{ title:{display:true,text:'AQI'}, beginAtZero:false } } }
    });

    function formatTime(ts) {
      return new Date(ts + 'Z').toLocaleTimeString('es-CO',{
        hour12:false,timeZone:'America/Bogota'
      });
    }

    async function cargarDatosIniciales() {
      const { data, error } = await supabase
        .from('calidadaire')
        .select('calidad_aire_valor, bateria_porcentaje, senal_red_dbm, estado, timestamp')
        .order('timestamp',{ ascending:true }).limit(50);
      if (error) return console.error(error);

      data.forEach(r => {
        const v    = parseFloat(r.calidad_aire_valor).toFixed(1);
        const time = formatTime(r.timestamp);
        const bat  = parseFloat(r.bateria_porcentaje).toFixed(1);
        const sig  = r.senal_red_dbm;

        // UI
        document.getElementById('currentValue').innerHTML   = `${v}<span class="unit">AQI</span>`;
        document.getElementById('currentTime').textContent  = time;
        document.getElementById('syncTime').textContent     = time;
        document.getElementById('batteryText').textContent  = `${bat}%`;
        document.getElementById('batteryFill').style.width  = `${bat}%`;
        document.getElementById('signalText').textContent   = `${sig} dBm`;
        document.getElementById('signalStatus').textContent = sig < -80 ? 'Débil' : 'Estable';

        const st = document.getElementById('currentStatus');
        if (v > 150) {
          st.className='status-badge status-danger'; st.innerHTML='<i class="fas fa-exclamation-circle me-1"></i> Peligroso';
        } else if (v>100) {
          st.className='status-badge status-warning'; st.innerHTML='<i class="fas fa-exclamation-triangle me-1"></i> Dañino';
        } else if (v>50) {
          st.className='status-badge status-warning'; st.innerHTML='<i class="fas fa-info-circle me-1"></i> Moderado';
        } else {
          st.className='status-badge status-good'; st.innerHTML='<i class="fas fa-check-circle me-1"></i> Bueno';
        }

        // Chart
        aqiChart.data.labels.push(time);
        aqiChart.data.datasets[0].data.push(parseFloat(v));
      });
      aqiChart.update();
    }

    async function suscribirseRealtime() {
      const channel = supabase
        .channel('realtime-calidadaire')
        .on('postgres_changes',{ event:'INSERT',schema:'public',table:'calidadaire' },
          ({ new:r })=>{
            console.log('[rt] payload:',r);
            const v    = parseFloat(r.calidad_aire_valor).toFixed(1);
            const time = formatTime(r.timestamp);
            const bat  = parseFloat(r.bateria_porcentaje).toFixed(1);
            const sig  = r.senal_red_dbm;

            document.getElementById('currentValue').innerHTML   = `${v}<span class="unit">AQI</span>`;
            document.getElementById('currentTime').textContent  = time;
            document.getElementById('syncTime').textContent     = time;
            document.getElementById('batteryText').textContent  = `${bat}%`;
            document.getElementById('batteryFill').style.width  = `${bat}%`;
            document.getElementById('signalText').textContent   = `${sig} dBm`;
            document.getElementById('signalStatus').textContent = sig < -80 ? 'Débil' : 'Estable';

            const st = document.getElementById('currentStatus');
            if (v > 150) {
              st.className='status-badge status-danger'; st.innerHTML='<i class="fas fa-exclamation-circle me-1"></i> Peligroso';
            } else if (v>100) {
              st.className='status-badge status-warning'; st.innerHTML='<i class="fas fa-exclamation-triangle me-1"></i> Dañino';
            } else if (v>50) {
              st.className='status-badge status-warning'; st.innerHTML='<i class="fas fa-info-circle me-1"></i> Moderado';
            } else {
              st.className='status-badge status-good'; st.innerHTML='<i class="fas fa-check-circle me-1"></i> Bueno';
            }

            aqiChart.data.labels.push(time);
            aqiChart.data.datasets[0].data.push(parseFloat(v));
            if (aqiChart.data.labels.length>50) {
              aqiChart.data.labels.shift();
              aqiChart.data.datasets[0].data.shift();
            }
            aqiChart.update();
          }
        );

      console.log('[rt] suscribiendo…');
      const { error } = await channel.subscribe();
      if (error) console.error(error);
    }

    async function toggleEstado() {
      const { data: rows } = await supabase
        .from('calidadaire')
        .select('*')
        .order('timestamp',{ascending:false})
        .limit(1);
      if (!rows.length) return;
      const last=rows[0];
      const nueva={
        calidad_aire_valor:       last.calidad_aire_valor,
        unidad_calidad_aire_id:   last.unidad_calidad_aire_id,
        estado:                   !last.estado,
        bateria_porcentaje:       last.bateria_porcentaje,
        senal_red_dbm:            last.senal_red_dbm
      };
      await supabase.from('calidadaire').insert([nueva]);
    }
    document.getElementById('toggleEstadoButton').addEventListener('click', toggleEstado);

    (async()=>{
      await cargarDatosIniciales();
      await suscribirseRealtime();
    })();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
