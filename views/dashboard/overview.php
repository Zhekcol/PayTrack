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
                <h3>$<?= number_format($ingresos, 2) ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-bg-danger shadow">
            <div class="card-body">
                <h5>Gastos</h5>
                <h3>$<?= number_format($gastos, 2) ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-bg-primary shadow">
            <div class="card-body">
                <h5>Balance</h5>
                <h3>$<?= number_format($balance, 2) ?></h3>
            </div>
        </div>
    </div>

</div>


</div>


<?php require 'layout/footer.php'; ?>
