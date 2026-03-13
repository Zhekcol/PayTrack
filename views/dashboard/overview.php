<?php
require_once __DIR__ . '/../../app/core/auth.php';
$usuario = $_SESSION['usuario'];
?>

<?php require 'layout/header.php'; ?>
<?php require 'layout/sidebar.php'; ?>

<div class="col-12 col-md-10 p-4">

    <h2 class="mb-4">Bienvenido, <?= htmlspecialchars($usuario['nombre']); ?> 👋</h2>

   <div class="row mt-4">

    <div class="col-md-4">
        <div class="card text-bg-success shadow">
            <div class="card-body">
                <h5>Ingresos</h5>
                <h3>$<?= number_format($ingresos, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-bg-danger shadow">
            <div class="card-body">
                <h5>Gastos</h5>
                <h3>$<?= number_format($gastos, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-bg-primary shadow">
            <div class="card-body">
                <h5>Balance</h5>
                <h3>$<?= number_format($balance, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <div class="row mt-4">

    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">

                <h5 class="mb-3">Ingresos vs Gastos</h5>

                <canvas id="graficaFinanzas"></canvas>
                <p id="mensajeSinDatos" class="text-muted text-center"></p>
            </div>
        </div>
    </div>
    <div class="col-md-8 mt-4">
        <div class="card shadow">
            <div class="card-body">

                <h5 class="mb-3">Gastos por Mes</h5>

                <canvas id="graficaGastosMes"></canvas>
                <p id="mensajeSinDatos" class="text-muted text-center"></p>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5>Gastos por categoría</h5>
            <div class="grafica-categorias">
                <canvas id="graficaCategorias"></canvas>
            </div>
            <p id="mensajeCategorias" class="text-muted text-center"></p>
        </div>
    </div>

    </div>

</div>

</div>

<?php require 'layout/footer.php'; ?>

<script>

const ingresos = <?= json_encode($ingresos) ?>;
const gastos = <?= json_encode($gastos) ?>;

const canvasFinanzas = document.getElementById('graficaFinanzas');

if (canvasFinanzas) {

    const ctx = canvasFinanzas.getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Ingresos', 'Gastos'],
            datasets: [{
                label: 'Monto',
                data: [ingresos, gastos],
                backgroundColor: [
                    'rgba(25,135,84,0.7)',
                    'rgba(220,53,69,0.7)'
                ]
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

}

fetch('/paytrack/public/index.php?url=obtener-gastos-mensuales')
.then(res => res.json())
.then(data => {

    if(data.length === 0){
        const mensajeSinDatos = document.getElementById("mensajeSinDatos");

        if(mensajeSinDatos){
            mensajeSinDatos.innerText = "Aún no hay gastos registrados.";
        }
    }

    const meses = [
        "Enero","Febrero","Marzo","Abril","Mayo","Junio",
        "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
    ];

    let gastosMensuales = new Array(12).fill(0);

    // Solo recorrer si es un array
    if(Array.isArray(data)){

        data.forEach(item => {

            const mes = parseInt(item.mes);

            if(mes >= 1 && mes <= 12){
                gastosMensuales[mes - 1] = parseFloat(item.total);
            }

        });

    }

    const canvasGastosMes = document.getElementById('graficaGastosMes');

    if(canvasGastosMes){

        const ctx = canvasGastosMes.getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Gastos por mes',
                    data: gastosMensuales,
                    borderColor: 'rgba(220,53,69,1)',
                    backgroundColor: 'rgba(220,53,69,0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    }

})
.catch(error => {

    console.error("Error cargando la gráfica:", error);

});

function generarColores(cantidad){
    const colores = [];

    for(let i = 0; i < cantidad; i++){
        const hue = Math.floor((360 / cantidad) * i);
        colores.push(`hsl(${hue}, 70%, 60%)`);
    }

    return colores;
}

fetch('/paytrack/public/index.php?url=obtener-gastos-categoria')
.then(res => res.json())
.then(data => {

    if(data.length === 0){
        const mensajeCategorias = document.getElementById("mensajeCategorias");

        if(mensajeCategorias){
            mensajeCategorias.innerText = "Aún no hay gastos registrados.";
        }
    }

    let categorias = [];
    let montos = [];

    if(Array.isArray(data)){
        data.forEach(item => {
            categorias.push(item.categoria);
            montos.push(parseFloat(item.total));
        });
    }

    if(montos.length === 0){
        return;
    }

    const canvasCategorias = document.getElementById('graficaCategorias');

    if(canvasCategorias){

        const ctx = canvasCategorias.getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: categorias,
                datasets: [{
                    label: 'Gastos',
                    data: montos,
                    backgroundColor: generarColores(montos.length)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

    }

});

</script>