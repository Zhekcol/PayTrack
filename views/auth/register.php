<?php
// Si hay un mensaje de la acción, lo recibimos aquí
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';  // success o error
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - PayTrack</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="/public/js/formularioRegistro.js" defer></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body">

                    <h3 class="text-center mb-4">Crear cuenta</h3>

                    <!-- Mostrar mensaje -->
                    <?php if ($mensaje): ?>
                        <div class="alert alert-<?php echo $tipo; ?>">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php endif; ?>

                    <form action="/auth/register_action" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required minlength="3">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Registrar</button>

                    </form>

                    <div class="text-center mt-2">
                        <small>
                            ¿Ya tienes cuenta?
                            <a href="/auth/login">Iniciar sesión</a>
                        </small>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
