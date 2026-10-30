<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administración - MUVI</title>
</head>
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
                <a href="<?= base_url('empleado/pedidos') ?>">
                    Consultar pedidos
                </a>
            </li>
            <li>
                <a href="<?= base_url('/') ?>">Ver la página principal</a>
            </li>
        </ul>
    </nav>

    <form action="<?= site_url('logout') ?>">
        <button type="submit">Cerrar sesión</button>
    </form>
    </div>