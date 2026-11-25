<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administración - MUVI</title>
<style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table.pagos { width: 100%; border-collapse: collapse; margin-top: 12px; }
        table.pagos th, table.pagos td { padding: 8px 10px; text-align: left; border-bottom: 1px solid #ddd; }
        table.pagos th { background: #343a40; color: #fff; }
        .tabla-scroll { overflow-x: auto; }
        .sin-usuario { color: #888; font-style: italic; }
    </style>
</head>
<body>

    <h1>Panel de administración de MUVI</h1>

    <p>
        Hola, <?= esc(session()->get('name')) ?> <?= esc(session()->get('last_name')) ?>.
    </p>

    <nav>
        <ul>
            <li>
                <a href="<?= base_url('admin/usuarios') ?>">
                    Gestionar usuarios y roles
                </a>
            </li>
            <li>
                <a href="<?= base_url('admin/peliculas') ?>">
                    Gestionar películas
                </a>
            </li>
            <li>
                <a href="<?= base_url('empleado/pedidos') ?>">
                    Consultar pedidos
                </a>
            </li>
            <li>
                <a href="<?= base_url('/') ?>">Ver la página principal</a>
            </li>
        </ul>
    </nav>

    <h2>Pagos registrados</h2>
    <?php $pagos = $pagos ?? []; ?>
    <p><?= count($pagos) ?> pago(s) almacenado(s).</p>

    <div class="tabla-scroll">
        <table class="pagos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Monto</th>
                    <th>Moneda</th>
                    <th>Método</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
            <?php if (! empty($pagos)): ?>
                <?php foreach ($pagos as $pago): ?>
                    <?php
                    // Las fechas se guardan en UTC: se muestran en la hora del cine.
                    $fecha = '—';
                    if (! empty($pago['created_at'])) {
                        $fecha = (new \DateTime($pago['created_at'], new \DateTimeZone('UTC')))
                            ->setTimezone(new \DateTimeZone('America/Argentina/Buenos_Aires'))
                            ->format('d/m/Y H:i');
                    }
                    $tieneUsuario = ! empty($pago['id_usuario']) && ! empty($pago['usuario_email']);
                    ?>
                    <tr>
                        <td><?= (int) $pago['id_pago'] ?></td>
                        <td><?= esc($fecha) ?></td>
                        <?php if ($tieneUsuario): ?>
                            <td><?= esc(trim($pago['usuario_nombre'] . ' ' . $pago['usuario_apellido'])) ?></td>
                            <td><?= esc($pago['usuario_email']) ?></td>
                        <?php else: ?>
                            <td colspan="2" class="sin-usuario">Sin usuario asociado</td>
                        <?php endif; ?>
                        <td>$<?= number_format((float) $pago['monto'], 2, ',', '.') ?></td>
                        <td><?= esc($pago['moneda']) ?></td>
                        <td><?= esc($pago['metodo_pago']) ?></td>
                        <td><?= esc($pago['estado']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">Todavía no hay pagos registrados.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <form action="<?= site_url('logout') ?>">
        <button type="submit">Cerrar sesión</button>
    </form>
    </div>