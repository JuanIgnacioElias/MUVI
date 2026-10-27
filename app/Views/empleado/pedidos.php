<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pedidos - MUVI</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #222; color: white; }
        .aviso { margin: 16px 0; }
    </style>
</head>
<body>
    <h1>Pedidos de clientes</h1>

    <p class="aviso">
        Usuario: <?= esc(session()->get('name')) ?>.
        Rol: <?= esc(session()->get('role')) ?>.
    </p>

    <p>
        <a href="<?= base_url('empleado/panel') ?>">Volver al panel</a>
    </p>

    <?php if (session()->getFlashdata('error')): ?>
        <p><?= esc(session()->getFlashdata('error')) ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID de compra</th>
                <th>Cliente</th>
                <th>Email</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php if (! empty($compras)): ?>
                <?php foreach ($compras as $compra): ?>
                    <tr>
                        <td><?= esc($compra['id_compra']) ?></td>
                        <td>
                            <?php if (! empty($compra['name'])): ?>
                                <?= esc($compra['name']) ?>
                                <?= esc($compra['last_name'] ?? '') ?>
                            <?php else: ?>
                                Cliente no identificado
                            <?php endif; ?>
                        </td>
                        <td><?= esc($compra['email'] ?? '—') ?></td>
                        <td>
                            <?= esc(number_format(
                                (float) $compra['total'],
                                2,
                                ',',
                                '.'
                            )) ?>
                        </td>
                        <td><?= esc($compra['estado']) ?></td>
                        <td><?= esc($compra['fecha_compra'] ?? '—') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Todavía no hay compras registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
