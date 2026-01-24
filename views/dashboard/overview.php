<?php
require_once __DIR__ . '/../../app/core/auth.php';
$usuario = $_SESSION['usuario'];
?>

<?php require 'layout/header.php'; ?>
<?php require 'layout/sidebar.php'; ?>

<div class="col-12 col-md-10 p-4">

    <h2 class="mb-4">Bienvenido, <?= htmlspecialchars($usuario['nombre']); ?> 👋</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <p class="mb-1"><strong>Correo:</strong> <?= htmlspecialchars($usuario['email']); ?></p>
            <p class="text-muted">Este será el resumen general del sistema.</p>
        </div>
    </div>

</div>


<?php require 'layout/footer.php'; ?>
