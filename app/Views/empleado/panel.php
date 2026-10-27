<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel del empleado - MUVI</title>
</head>
<body>
    <h1>Panel del empleado</h1>

    <p>
        Bienvenido/a, <?= esc(session()->get('name')) ?>.
    </p>

    <nav>
        <a href="<?= base_url('empleado/pedidos') ?>">
            Consultar pedidos de clientes
        </a>

        <a href="<?= base_url('/') ?>">Volver a la página principal</a>
    </nav>

    <form action="<?= base_url('logout') ?>" method="get">
        <button type="submit">Cerrar sesión</button>
    </form>
</body>
</html>
