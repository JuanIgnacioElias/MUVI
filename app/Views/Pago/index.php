<?= view('templates/header') ?>
    <div class="container mt-5" style="max-width: 650px;">
        
        <?php 
        // 1. Instanciamos la sesión nativa
        $session = session();
        
        // 2. Capturamos la variable exacta que definiste en tu filtro, pasándola a mayúsculas
        $rolUsuario = strtoupper((string) $session->get('role')); 

        // 3. Condición de Seguridad formal: Si es ADMINISTRADOR o EMPLEADO
        $esPersonalCine = ($rolUsuario === 'ADMINISTRADOR' || $rolUsuario === 'EMPLEADO'); 
        
        if (!$esPersonalCine): ?>
            
            <!-- =============================================================== -->
            <!-- VISTA DEL CLIENTE: Formulario de Pago directo                   -->
            <!-- =============================================================== -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h3 class="card-title mb-0">💳 Finalizar tu Compra</h3>
                </div>
                <div class="card-body p-4 bg-white">
                    <form action="<?= base_url('pago/guardarWeb') ?>" method="POST">
                        <?= csrf_field() ?>
                     
                        <!-- MONTO AUTOMÁTICO DESDE EL CARRITO -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Monto Total a Pagar:</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light text-dark fw-bold">$</span>
                                <input type="number" id="monto_input" name="monto" class="form-control bg-light fw-bold text-dark" readonly required>
                            </div>
                            <small class="text-muted d-block mt-1">Este valor calcula automáticamente tus entradas y combos seleccionados.</small>
                        </div>

                        <!-- MONEDA -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Moneda:</label>
                            <select name="moneda" class="form-select" required>
                                <option value="ars" selected>ars (Pesos Argentinos)</option>
                                <option value="usd">usd (Dólares)</option>
                            </select>
                        </div>

                        <!-- MÉTODO DE PAGO -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Método de Pago:</label>
                            <select name="metodo_pago" class="form-select" required>
                                <option value="">-- Selecciona una opción --</option>
                                <option value="mercado pago">Mercado Pago</option>
                                <option value="tarjeta">Tarjeta de Crédito / Débito</option>
                                <option value="efectivo">Efectivo (Boletería)</option>
                            </select>
                        </div>

                        <!-- ESTADO OCULTO -->
                        <input type="hidden" name="estado" value="Pendiente">

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="btn-confirmar">Confirmar Pago</button>
                            <a href="<?= base_url('cartelera') ?>" class="btn btn-link text-muted text-center mt-2">Volver a la Cartelera</a>
                        </div>
                    </form>
                </div>
            </div>

        <?php else: ?>

            <!-- =============================================================== -->
            <!-- VISTA DEL PERSONAL: Registro de Pagos (Modo Administración)    -->
            <!-- =============================================================== -->
            <div class="card shadow-sm w-100" style="max-width: 1000px; margin: 0 auto; min-width: 600px;">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title mb-0">📋 Registro de Pagos (Rol: <?= esc($rolUsuario) ?>)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead>
                                <tr class="table-secondary">
                                    <th>ID</th>
                                    <th>Monto</th>
                                    <th>Moneda</th>
                                    <th>Método</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pagos)): foreach ($pagos as $pago): ?>
                                    <tr>
                                        <td><?= $pago['id_pago'] ?></td>
                                        <td>$<?= number_format($pago['monto'], 2) ?></td>
                                        <td><?= esc($pago['moneda']) ?></td>
                                        <td><?= esc($pago['metodo_pago']) ?></td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                <?= esc($pago['estado']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No hay registros de pago cargados en el sistema.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>

    <!-- SCRIPT DE SUMATORIA AUTOMÁTICA -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const montoInput = document.getElementById('monto_input');
            const btnConfirmar = document.getElementById('btn-confirmar');
            
            if (montoInput && btnConfirmar) {
                const carrito = JSON.parse(localStorage.getItem('carritoCine')) || [];
                let totalCalculado = 0;

                carrito.forEach(item => {
                    totalCalculado += parseFloat(item.precio) * parseInt(item.cantidad);
                });

                if (totalCalculado > 0) {
                    montoInput.value = Math.round(totalCalculado);
                } else {
                    montoInput.value = 0;
                    btnConfirmar.disabled = true;
                }
            }
        });
    </script>

    <!-- Limpieza automática de la memoria del navegador tras guardar -->
    <?php if (session()->getFlashdata('clear_cart')): ?>
        <script>
            localStorage.removeItem('carritoCine');
            console.log("Transacción exitosa: Carrito vaciado de la memoria local.");
        </script>
    <?php endif; ?>

<?= view('templates/footer') ?>
