<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="container mt-4">

    <h3><i class="bi bi-cash-coin"></i> Ingresos</h3>
    <div id="contenedorAlertas"></div>
    <form action="/ingresos/store" method="POST" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="descripcion" id="descripcion" class="form-control" placeholder="Descripción" required>
            <small class="text-danger" id="errorDescripcion"></small>
        </div>

        <div class="col-md-3">
            <input type="text" name="monto" id="monto" class="form-control" placeholder="Monto" required onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
            <small class="text-danger" id="errorMonto"></small>
        </div>

        <div class="col-md-3">
            <input type="date" name="fecha" id="fecha" class="form-control" required>
            <small class="text-danger" id="errorFecha"></small>
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

<nav>
    <ul class="pagination justify-content-center">

        <!-- Anterior -->
        <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="?pagina=<?= $pagina - 1 ?>">Anterior</a>
        </li>

        <!-- Números -->
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                <a class="page-link" href="?pagina=<?= $i ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Siguiente -->
        <li class="page-item <?= ($pagina >= $totalPaginas) ? 'disabled' : '' ?>">
            <a class="page-link" href="?pagina=<?= $pagina + 1 ?>">Siguiente</a>
        </li>

    </ul>
</nav>

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

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function(){
const modalEliminar = document.getElementById('modalEliminar');
modalEliminar.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');

    const btnEliminar = document.getElementById('btnEliminar');
    btnEliminar.href = `/ingresos/delete?id=${id}`;
});

const descripcion = document.getElementById("descripcion");
const monto = document.getElementById("monto");
const fecha = document.getElementById("fecha");

function validarDescripcion() {
    const error = document.getElementById("errorDescripcion");

    if (descripcion.value.trim() === "") {
        descripcion.classList.add("is-invalid");
        descripcion.classList.remove("is-valid");
        error.textContent = "La descripción es obligatoria.";
        return false;
    }

    descripcion.classList.remove("is-invalid");
    descripcion.classList.add("is-valid");
    error.textContent = "";
    return true;
}

function validarMonto() {
    const error = document.getElementById("errorMonto");

    let valor = monto.value.trim();

    valor = valor.replace(/\./g, "").replace(",", ".");

    valor = parseFloat(valor);

    if (isNaN(valor) || valor <= 0) {
        monto.classList.add("is-invalid");
        monto.classList.remove("is-valid");
        error.textContent = "El monto debe ser mayor a 0.";
        return false;
    }

    monto.classList.remove("is-invalid");
    monto.classList.add("is-valid");
    error.textContent = "";
    return true;
}

function validarFecha() {
    const error = document.getElementById("errorFecha");

    if (!fecha.value) {
        fecha.classList.add("is-invalid");
        fecha.classList.remove("is-valid");
        error.textContent = "La fecha es obligatoria.";
        return false;
    }

    fecha.classList.remove("is-invalid");
    fecha.classList.add("is-valid");
    error.textContent = "";
    return true;
}

descripcion.addEventListener("input", validarDescripcion);
monto.addEventListener("input", validarMonto);
fecha.addEventListener("change", validarFecha);

function validarFormulario() {
    const v1 = validarDescripcion();
    const v2 = validarMonto();
    const v3 = validarFecha();

    return v1 && v2 && v3;
}

function mostrarAlerta(mensaje, tipo = "success") {

    const contenedor = document.getElementById("contenedorAlertas");

    const alerta = document.createElement("div");
    alerta.className = `alert alert-${tipo} alert-dismissible fade show mt-2`;
    alerta.role = "alert";

    alerta.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    contenedor.innerHTML = "";
    contenedor.appendChild(alerta);

    setTimeout(() => {
        alerta.remove();
    }, 5000);
}

function limpiarFormulario(form) {

    const inputs = form.querySelectorAll("input");

    inputs.forEach(input => {
        input.classList.remove("is-valid", "is-invalid");
    });

    const errores = form.querySelectorAll("small");

    errores.forEach(error => {
        error.textContent = "";
    });

}

document.querySelector("form").addEventListener("submit", function(e){

    e.preventDefault();

    if (!validarFormulario()) return;

    const form = this;
    const formData = new FormData(form);

    fetch("/ingresos/store", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            mostrarAlerta("Ingreso agregado correctamente.", "success");

            form.reset();
            limpiarFormulario(form);
            setTimeout(() => {
            location.reload();
        }, 1000);
        } else {
            mostrarAlerta(data.error || "Error al guardar.", "danger");
        }

    })
    .catch(() => {
        mostrarAlerta("Error de conexión", "danger");
    });

});

});
</script>
