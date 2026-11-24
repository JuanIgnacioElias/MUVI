<?= view('templates/header') ?>
    <div class="container mt-5" style="max-width: 650px;">
        
        <?php 
        $session = session();
        
        // Capturamos la variable exacta que definiste en tu filtro, pasándola a mayúsculas
        $rolUsuario = strtoupper((string) $session->get('role')); 

        // Condición de Seguridad formal: Si es ADMINISTRADOR o EMPLEADO
        $esPersonalCine = ($rolUsuario === 'ADMINISTRADOR' || $rolUsuario === 'EMPLEADO'); 
        
        if (!$esPersonalCine): ?>
            
                        <!-- =============================================================== -->
            <!-- VISTA DEL CLIENTE: Resumen de Compra y Formulario de Pago       -->
            <!-- =============================================================== -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h3 class="card-title mb-0">💳 Finalizar tu Compra</h3>
                </div>
                <div class="card-body p-4 bg-white">

                    <!-- DETALLE / HISTORIAL DE LO QUE VA A PAGAR -->
                    <div class="mb-4 p-3 bg-light rounded border">
                        <h5 class="fw-bold text-dark mb-3">🧾 Resumen de tu Pedido</h5>

                        <?php if (!empty($resumen['funcion'])): ?>
                            <div class="mb-2 pb-2 border-bottom">
                                <strong>🎬 Película:</strong> <?= esc($resumen['funcion']['pelicula_titulo'] ?? 'Función #' . $resumen['funcion']['id_funcion']) ?><br>
                                <small class="text-muted">
                                    📅 <?= date('d/m/Y', strtotime($resumen['funcion']['fecha'])) ?> | 
                                    🕒 <?= substr($resumen['funcion']['hora_ingreso'], 0, 5) ?> hs | 
                                    📍 <?= esc($resumen['funcion']['sala_nombre'] ?? 'Sala ' . $resumen['funcion']['id_sala']) ?>
                                </small>
                            </div>
                        <?php endif; ?>

                        <ul class="list-group list-group-flush mb-2">
                            <!-- Entradas -->
                            <?php if ($resumen['entradas_cant'] > 0): ?>
                                <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <strong>🎟️ Entradas (x<?= $resumen['entradas_cant'] ?>)</strong>
                                        <span class="badge <?= $resumen['entradas_es_4d'] ? 'bg-danger' : 'bg-secondary' ?> ms-1">
                                            <?= $resumen['entradas_es_4d'] ? 'Sala 4D' : 'Estándar' ?>
                                        </span>
                                        <div class="small text-muted">
                                            Butacas: <?= esc(implode(', ', $resumen['butacas'])) ?> ($<?= number_format($resumen['entrada_unit'], 0, ',', '.') ?> c/u)
                                        </div>
                                    </div>
                                    <span class="fw-bold">$<?= number_format($resumen['entradas_total'], 0, ',', '.') ?></span>
                                </li>
                            <?php endif; ?>

                            <!-- Combos -->
                            <?php if (!empty($resumen['combos'])): ?>
                                <?php foreach ($resumen['combos'] as $c): ?>
                                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <strong>🍿 Combo:</strong> <?= esc($c['comida']) ?> + <?= esc($c['bebida']) ?>
                                        </div>
                                        <span class="fw-bold">$<?= number_format($c['precio'], 0, ',', '.') ?></span>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if ($resumen['entradas_cant'] === 0 && empty($resumen['combos'])): ?>
                                <li class="list-group-item bg-transparent text-muted text-center px-0">
                                    No hay entradas ni combos seleccionados todavía.
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <form action="<?= base_url('pago/guardarWeb') ?>" method="POST">
                        <?= csrf_field() ?>
                     
                        <!-- MONTO AUTOMÁTICO CALCULADO EN EL SERVIDOR -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Monto Total a Pagar:</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light text-dark fw-bold">$</span>
                                <input type="number" id="monto_input" name="monto" 
                                       value="<?= (int) round($resumen['total_general']) ?>" 
                                       class="form-control bg-light fw-bold text-dark" readonly required>
                            </div>
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
                            <button type="submit" class="btn btn-primary btn-lg" id="btn-confirmar"
                                    <?= (($resumen['total_general'] ?? 0) <= 0) ? 'disabled' : '' ?>>
                                Confirmar Pago
                            </button>
                            <a href="<?= base_url('funcion') ?>" class="btn btn-link text-muted text-center mt-2">Volver a la Cartelera</a>
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

<?= view('templates/footer') ?>
