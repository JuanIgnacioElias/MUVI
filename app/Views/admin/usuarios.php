<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - Gestión de Usuarios</title>
    <!-- Agrego un estilo CSS básico y limpio para que sea legible -->
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f6f9; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-danger { background-color: #f8d7da; color: #721c24; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #343a40; color: white; }
        tr:hover { background-color: #f1f1f1; }
        select { padding: 6px; border-radius: 4px; border: 1px solid #ccc; }
        button { background-color: #007bff; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .btn-volver { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>

<div class="container">
    <a href="<?= base_url('admin/dashboard') ?>" class="btn-volver">&larr; Volver al Dashboard</a>
    
    <h2>Gestión y Asignación de Roles</h2>

    <!-- Mensajes de Feedback -->
    <?php if (session()->getFlashdata('exito')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('exito') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>CUIL</th>
                <th>Rol Actual</th>
                <th>Cambiar Rol</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($usuarios) && is_array($usuarios)): ?>
                <?php foreach ($usuarios as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= esc($user['name']) . ' ' . esc($user['last_name']) ?></td>
                        <td><?= esc($user['email']) ?></td>
                        <td><?= esc($user['cuil']) ?></td>
                        <td>
                            <strong><?= esc($user['role']) ?></strong>
                        </td>
                        <td>
                            <!-- Formulario individual por cada fila -->
                            <form action="<?= base_url('admin/usuarios/actualizar-rol') ?>" method="POST" style="display:inline-flex; gap: 10px;">
                                <?= csrf_field() ?> <!-- Protección CSRF integrada de CodeIgniter 4 -->
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                
                                <select name="role">
                                    <option value="CLIENTE" <?= $user['role'] === 'CLIENTE' ? 'selected' : '' ?>>Cliente</option>
                                    <option value="EMPLEADO" <?= $user['role'] === 'EMPLEADO' ? 'selected' : '' ?>>Empleado</option>
                                    <option value="ADMINISTRADOR" <?= $user['role'] === 'ADMINISTRADOR' ? 'selected' : '' ?>>Administrador</option>
                                </select>
                                
                                <button type="submit">Actualizar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No se encontraron usuarios registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>