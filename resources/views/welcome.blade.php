<button id="insertarBtn" style="padding: 10px 20px; background-color: #38bdf8; color: white; border: none; border-radius: 5px;">
    Insertar fila en termómetro
</button>

<p id="mensaje"></p>

<script>
document.getElementById('insertarBtn').addEventListener('click', function () {
    fetch('/insertar-termometro', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('mensaje').innerText = data.mensaje;
    })
    .catch(error => {
        document.getElementById('mensaje').innerText = 'Error al insertar la fila.';
        console.error('Error:', error);
    });
});
</script>
