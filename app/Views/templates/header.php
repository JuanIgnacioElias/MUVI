<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo ?? 'Inicio') ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/hedfoot.css') ?>">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<header>
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
        <a class="navbar-brand mb-0 h1" href="<?= site_url('/') ?>">
            <img src="<?= base_url('assets/img/MUVILOGAZO.png') ?>" alt="Logo" width="120px" height="65px">
        </a>
        <ul class="nav justify-content-center flex-grow-1 d-none d-md-flex gap-4 m-0 p-0">
            <li class="nav-item"><a href="#" class="nav-link text-white fw-bold custom-nav-link text-uppercase">Entradas</a></li>
            <li class="nav-item"><a href="#" class="nav-link text-white fw-bold custom-nav-link text-uppercase">Cartelera</a></li>
            <li class="nav-item"><a href="#" class="nav-link text-white fw-bold custom-nav-link text-uppercase">Combos</a></li>
            <li class="nav-item"><a href="#" class="nav-link text-white fw-bold custom-nav-link text-uppercase">Boletería</a></li>
        </ul>
        <div class="d-flex align-items-center text-white">
            <?php if (session()->get('isLoggedIn')): ?>
                <span class="me-3 small">Bienvenido <?= esc(strtok(session()->get('name') ?? '', ' ')) ?>!!!</span>
                <a href="<?= site_url('logout') ?>" class="btn btn-outline-danger btn-sm">Cerrar Sesión</a>
            <?php else: ?>
                <a href="<?= site_url('login') ?>" class="btn btn-primary btn-sm me-2">Ingresar</a>
                <a href="<?= site_url('register') ?>" class="btn btn-primary btn-sm">Registrarse</a>
            <?php endif; ?>
            <a href="<?= site_url('/cliente/mi-cuenta') ?>"><img src="<?= base_url('assets/img/userimage.png') ?>" alt="userlogo" width="50px" height="50px"></a>
        </div>

    </div>
</nav>
</header>

<div class="container flex-grow-1">
