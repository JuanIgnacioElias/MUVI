<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Pago</title>
    <!-- Usamos Bootstrap para que combine con el resto de tu plataforma de cine Muvi -->
    <link href="https://jsdelivr.net" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5" style="max-width: 500px;">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h3 class="card-title mb-0">💳 Registrar Pago</h3>
            </div>
            <div class="card-body">
                
                <form action="<?= base_url('pago/guardarWeb') ?>" method="POST">
                    <?= csrf_field() ?>
                 
                    <!-- MONTO AUTOMÁTICO: Bloqueado para que el usuario no tipee el precio libremente -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Monto Total a Pagar:</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" id="monto_input" name="monto" class="form-control bg-white" readonly required>
                        </div>
                        <small class="text-muted">Este valor se calcula sumando tus entradas y combos seleccionados.</small>
                    </div>

                    <!-- MONEDA CONTROLADA -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Moneda:</label>
                        <select name="moneda" class="form-select" required>
                            <option value="ars" selected>ars (Pesos Argentinos)</option>
                            <option value="usd">usd (Dólares)</option>
                        </select>
                    </div>

                    <!-- MÉTODO DE PAGO CONTROLADO -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Método de Pago:</label>
                        <select name="metodo_pago" class="form-select" required>
                            <option value="">-- Selecciona una opción --</option>
                            <option value="mercado pago">Mercado Pago</option>
                            <option value="tarjeta">Tarjeta de Crédito / Débito</option>
                            <option value="efectivo">Efectivo (Boletería)</option>
                        </select>
                    </div>

                    <!-- ESTADO PREDETERMINADO -->
                    <input type="hidden" name="estado" value="Pendiente">

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="<?= base_url('pago') ?>" class="btn btn-secondary">Volver atrás</a>
                        <button type="submit" class="btn btn-primary" id="btn-confirmar">Guardar Pago</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- SCRIPT DE SUMATORIA AUTOMÁTICA -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Lee el carrito guardado en el navegador (ajusta 'carritoCine' si usas otro nombre en tu LocalStorage)
            const carrito = JSON.parse(localStorage.getItem('carritoCine')) || [];
            const montoInput = document.getElementById('monto_input');
            const btnConfirmar = document.getElementById('btn-confirmar');

            let totalCalculado = 0;

            // Multiplica el precio de cada entrada/combo por su cantidad seleccionada
            carrito.forEach(item => {
                totalCalculado += parseFloat(item.precio) * parseInt(item.cantidad);
            });

            // Inyecta el resultado redondeado a Entero (INT) requerido por tu base de datos
            if (totalCalculado > 0) {
                montoInput.value = Math.round(totalCalculado);
            } else {
                montoInput.value = 0;
                btnConfirmar.disabled = true;
                alert("Atención: El carrito de compras está vacío. Selecciona entradas o combos antes de proceder.");
            }
        });
    </script>
</body>
</html>

