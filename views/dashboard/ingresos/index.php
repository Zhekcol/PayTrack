<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="container mt-4">

    <h3><i class="bi bi-cash-coin"></i> Ingresos</h3>

    <form action="/ingresos/store" method="POST" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="descripcion" class="form-control" placeholder="Descripción">
        </div>

        <div class="col-md-3">
            <input type="number" name="monto" step="0.01" class="form-control" placeholder="Monto">
        </div>

        <div class="col-md-3">
            <input type="date" name="fecha" class="form-control">
        </div>

        <div class="col-md-2">
            <button class="btn btn-success w-100">Agregar</button>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Monto</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ingresos as $ingreso): ?>
            <tr>
                <td><?= $ingreso['fecha'] ?></td>
                <td><?= htmlspecialchars($ingreso['descripcion']) ?></td>
                <td>$<?= number_format($ingreso['monto'], 0, ',', '.') ?></td>
                <td>
                    <a 
                        href="/ingresos/edit?id=<?= $ingreso['id']; ?>" 
                        class="btn btn-warning">
                        Editar
                    </a>
                    <button 
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEliminar"
                        data-id="<?= $ingreso['id']; ?>">
                        Eliminar
                    </button>

                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
<div class="modal fade" id="modalEliminar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Confirmar eliminación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar este ingreso?
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancelar
        </button>
        <a href="#" id="btnEliminar" class="btn btn-danger">
          Eliminar
        </a>
      </div>

    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const modalEliminar = document.getElementById('modalEliminar');
modalEliminar.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');

    const btnEliminar = document.getElementById('btnEliminar');
    btnEliminar.href = `/ingresos/delete?id=${id}`;
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
