<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Compras - Cine</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
</head>
    <?= view('templates/header') ?>
    <div class="container" style="margin-top: 50px">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>🎬 Historial de Compras</h1>
            <!-- Botón para ir al formulario separado -->
            <a href="<?= base_url('compras/nuevo') ?>" class="btn btn-primary">➕ Registrar Nueva Compra</a>
        </div>

        <!-- Mensajes de Éxito -->
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">Compras Registradas</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Película/Función</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($compras)): foreach ($compras as $compra): ?>
                                <tr>
                                    <td><?= $compra['id_compra'] ?></td>
                                    <td><?= esc($compra['titulo_pelicula'] ?? 'N/A') ?></td>
                                    <td>$<?= number_format($compra['total'], 2) ?></td>
                                    <td>
                                        <span class="badge <?= $compra['estado'] === 'Completado' ? 'bg-success' : ($compra['estado'] === 'Pendiente' ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                            <?= esc($compra['estado']) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($compra['created_at']) ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">No hay compras registradas aún.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://jsdelivr.net"></script>
    
    <?= view('templates/footer') ?>