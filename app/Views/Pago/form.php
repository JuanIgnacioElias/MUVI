<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Pago</title>
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
                 
                    <!-- MONTO AUTOMÁTICO -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Monto Total a Pagar:</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" id="monto_input" name="monto" class="form-control bg-white" value="<?= esc($resumen['total_redondeado']) ?>" readonly required>
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

</body>
</html>



