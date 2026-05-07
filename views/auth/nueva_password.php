<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PayTrack</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow mx-auto" style="max-width:400px;">
        <div class="card-body">

            <h4 class="mb-4 text-center">
                Nueva contraseña
            </h4>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="/paytrack/public/index.php?url=auth/reset-password" method="POST">

                <div class="mb-3">
                    <label>Nueva contraseña</label>

                    <input 
                    type="password"
                    name="password"
                    class="form-control"
                    minlength="8"
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$"
                    required>
                    <small class="text-muted">
                        Debe tener mínimo 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.
                    </small>
                </div>

                <button class="btn btn-success w-100">
                    Cambiar contraseña
                </button>

            </form>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>