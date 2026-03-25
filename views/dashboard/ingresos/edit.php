<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-body">

            <h4 class="mb-4">Editar ingreso</h4>
            <div id="contenedorAlertas"></div>

            <form action="/ingresos/update" method="POST">

                <input type="hidden" name="id" value="<?= $ingreso['id']; ?>">

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <input 
                        type="text" 
                        name="descripcion"
                        id="descripcion"
                        class="form-control"
                        value="<?= htmlspecialchars($ingreso['descripcion']); ?>"
                        required>
                        <small class="text-danger" id="errorDescripcion"></small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Monto</label>
                    <input 
                        type="text" 
                        name="monto"
                        id="monto"
                        class="form-control"
                        value="<?= intval($ingreso['monto']); ?>"
                        required
                        onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                        <small class="text-danger" id="errorMonto"></small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha</label>
                    <input 
                        type="date" 
                        name="fecha"
                        id="fecha"
                        class="form-control"
                        value="<?= $ingreso['fecha']; ?>"
                        required>
                        <small class="text-danger" id="errorFecha"></small>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/ingresos" class="btn btn-secondary">
                        Cancelar
                    </a>
                    <button class="btn btn-success">
                        Guardar cambios
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

<script>


document.addEventListener("DOMContentLoaded", function(){

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

document.querySelector("form").addEventListener("submit", function(e){

    e.preventDefault();

    if (!validarFormulario()) return;

    const form = this;
    const formData = new FormData(form);

    fetch("/ingresos/update", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

    console.log(data);
    

        if (data.success) {
            mostrarAlerta("Ingreso actualizado correctamente.", "success");

            setTimeout(() => {
                window.location.href = "/ingresos";
            }, 1500);
        } else {
            mostrarAlerta(data.error || "Error al actualizar", "danger");
        }

    })
    .catch(() => {
        mostrarAlerta("Error de conexión", "danger");
    });

});

});
</script>