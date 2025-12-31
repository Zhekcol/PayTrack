<?php
session_start();

// Protección: solo usuarios logueados
if (!isset($_SESSION['usuario'])) {
    header('Location: /auth/login');
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - PayTrack</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body text-center">

            <h3>Bienvenido 👋</h3>
            <p class="mt-3">
                <strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong><br>
                <?php echo htmlspecialchars($usuario['email']); ?>
            </p>

            <a href="/auth/logout" class="btn btn-danger mt-3">
                Cerrar sesión
            </a>

        </div>
    </div>
</div>

</body>
</html>
