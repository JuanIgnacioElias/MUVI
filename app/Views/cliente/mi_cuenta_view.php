<?php 
// 1. Instanciamos la sesión directamente en la vista. 
// Al linter de VS Code le encanta esto porque sabe qué es el objeto $session.
$session = session(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Cuenta - Cliente</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f4f9; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 500px; }
        h1 { color: #333; }
        .badge { background: #28a745; color: white; padding: 5px 10px; border-radius: 4px; font-size: 0.9em; }
        a { display: inline-block; margin-top: 15px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>

<?= view('templates/header') ?>


    <div class="card">
        <!-- 2. Traemos los datos directo del objeto usando sus métodos orientados a objetos -->
        <h1>¡Hola, <?= esc(session()->get('name')) ?> <?= esc(session()->get('last_name')) ?>!</h1>
        <p>Bienvenido a tu panel de usuario.</p>
        <hr>
        <p><strong>Tu Email:</strong> <?php echo $session->get('email'); ?></p>
        <p><strong>Tu Rol actual:</strong> <span class="badge"><?php echo $session->get('role'); ?></span></p>
        
        <a href="/logout">Cerrar Sesión</a>
    </div>

    <?= view('templates/footer') ?>

</body>
</html>
