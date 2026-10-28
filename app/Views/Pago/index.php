
    <?= view('templates/header') ?>
    <div class="container" style="margin-top: 50px">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>💳 Historial de Pagos</h1>
            <a href="<?php echo base_url('pago/nuevo'); ?>" class="btn btn-primary">Registrar Nuevo Pago</a>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">Pagos Registrados</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
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
                                        <span class="badge <?= $pago['estado'] === 'Aprobado' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                            <?= esc($pago['estado']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">No hay pagos registrados aún.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?= view('templates/footer') ?><    
