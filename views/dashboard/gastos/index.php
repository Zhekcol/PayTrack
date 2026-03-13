<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

<h3 class="mb-3">💸 Gastos</h3>

<form action="/gastos/store" method="POST" class="row g-2 mb-4">
    <div class="col-md-3">
        <input type="text" name="categoria" class="form-control" placeholder="Categoría" required>
    </div>
    <div class="col-md-3">
        <input type="text" name="descripcion" class="form-control" placeholder="Descripción" required>
    </div>
    <div class="col-md-2">
        <input type="number" name="monto" step="0.01" class="form-control" placeholder="Monto" required>
    </div>
    <div class="col-md-2">
        <input type="date" name="fecha" class="form-control" required>
    </div>
    <div class="col-md-2">
        <button class="btn btn-danger w-100">Agregar</button>
    </div>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Categoría</th>
            <th>Descripción</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($gastos as $gasto): ?>
            <tr>
                <td><?= htmlspecialchars($gasto['categoria']) ?></td>
                <td><?= htmlspecialchars($gasto['descripcion']) ?></td>
                <td>$<?= number_format($gasto['monto'], 0, ',', '.') ?></td>
                <td><?= $gasto['fecha'] ?></td>
                <td>
                    <a href="/gastos/delete?id=<?= $gasto['id'] ?>" class="btn btn-danger">
                        Eliminar
                    </a>
                    <a href="/gastos/edit?id=<?= $gasto['id'] ?>" class="btn btn-warning">
                        Editar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
