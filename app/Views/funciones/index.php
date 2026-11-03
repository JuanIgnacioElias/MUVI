<?php /** @var string $rol_usuario */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cartelera de Funciones</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link rel="stylesheet" href="/css/estilos_sala.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>🎭 Cartelera de Funciones</h2>
            
            <!-- ⚡ FILTRO: Solo el Admin ve el botón de crear -->
            <?php if ($rol_usuario === 'ADMINISTRADOR' || $rol_usuario === 'EMPLEADO'): ?>
                <a href="<?= base_url('funciones/create') ?>" class="btn btn-primary">+ Nueva Función</a>
            <?php endif; ?>
        </div>

        <!-- Mensajes de Éxito o Error -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="card shadow-sm">
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
                        <?php if(!empty($funciones) && is_array($funciones)): ?>
                            <?php foreach($funciones as $funcion): ?>
                                <tr>
                                    <td><strong>#<?= $funcion['id_funcion'] ?></strong></td>
                                    <td><?= date('d/m/Y', strtotime($funcion['fecha'])) ?></td>
                                    <td>
                                        <span class="badge bg-success"><?= substr($funcion['hora_ingreso'], 0, 5) ?></span> a 
                                        <span class="badge bg-secondary"><?= substr($funcion['hora_salida'], 0, 5) ?></span>
                                    </td>
                                    <td><?= $funcion['id_sala'] ?></td>
                                    <td><?= $funcion['id_pelicula'] ?></td>
                                    
                                    <td class="text-center">
                                        <!-- ⚡ FILTROS DE BOTONES SEGÚN EL ROL -->
                                        <?php if ($rol_usuario === 'ADMINISTRADOR' || $rol_usuario === 'EMPLEADO'): ?>
                                            <!-- Lo que ve el Admin (El CRUD) -->
                                            <a href="<?= base_url('funciones/edit/'.$funcion['id_funcion']) ?>" class="btn btn-warning btn-sm me-1">Editar</a>
                                            <a href="<?= base_url('funciones/delete/'.$funcion['id_funcion']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro?')">Eliminar</a>
                                        <?php else: ?>
                                            <!-- Lo que ve el Cliente -->
                                            <a href="<?= base_url('funciones/seleccionar/'.$funcion['id_funcion']) ?>" class="btn btn-info btn-sm text-white fw-bold">Seleccionar</a>
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
    </div>
</body>
</html>
