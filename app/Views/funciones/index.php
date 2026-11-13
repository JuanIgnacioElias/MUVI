<<<<<<< HEAD
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cartelera de Funciones</title>
    <link rel="stylesheet" href="/css/estilos_sala.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>🎭 Cartelera de Funciones</h2>
            
            <!-- ⚡ FILTRO: Solo el Admin ve el botón de crear -->
            <?php if ($rol_usuario === 'admin' || $rol_usuario === 'empleado'): ?>
                <a href="<?= base_url('funciones/create') ?>" class="btn btn-primary">+ Nueva Función</a>
            <?php endif; ?>
        </div>
=======
<?php /** @var string $rol_usuario */ ?>
<?php $puedeGestionar = in_array($rol_usuario, ['ADMINISTRADOR', 'EMPLEADO'], true); ?>
<?= view('templates/header', ['titulo' => 'Cartelera de Funciones']) ?>
>>>>>>> e9371be32bdfdbf2b1acbc34cc3bad9f8b9c7308

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>🎭 Cartelera de Funciones</h2>

    <!-- Solo empleado y administrador pueden crear funciones -->
    <?php if ($puedeGestionar): ?>
        <a href="<?= site_url('funcion/create') ?>" class="btn btn-primary">+ Nueva función</a>
    <?php endif; ?>
</div>

<!-- Mensajes de éxito o error -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div class="card shadow-sm mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Horario</th>
                    <th>Sala ID</th>
                    <th>Película ID</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty($funciones) && is_array($funciones)): ?>
                    <?php foreach ($funciones as $funcion): ?>
                        <tr>
                            <td><strong>#<?= esc($funcion['id_funcion']) ?></strong></td>
                            <td><?= date('d/m/Y', strtotime($funcion['fecha'])) ?></td>
                            <td>
                                <span class="badge bg-success"><?= substr($funcion['hora_ingreso'], 0, 5) ?></span> a
                                <span class="badge bg-secondary"><?= substr($funcion['hora_salida'], 0, 5) ?></span>
                            </td>
                            <td><?= esc($funcion['id_sala']) ?></td>
                            <td><?= esc($funcion['id_pelicula']) ?></td>

                            <td class="text-center">
                                <?php if ($puedeGestionar): ?>
                                    <!-- Empleado y administrador: editar y eliminar -->
                                    <a href="<?= site_url('funcion/edit/' . $funcion['id_funcion']) ?>" class="btn btn-warning btn-sm me-1">Editar</a>
                                    <a href="<?= site_url('funcion/delete/' . $funcion['id_funcion']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que querés eliminar esta función?')">Eliminar</a>
                                <?php else: ?>
                                    <!-- Cliente: solo seleccionar -->
                                    <a href="<?= site_url('funcion/seleccionar/' . $funcion['id_funcion']) ?>" class="btn btn-info btn-sm text-white fw-bold">Seleccionar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No hay funciones disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('templates/footer') ?>
