<?php 
// 1. Instanciamos la sesión directamente en la vista. 
// Al linter de VS Code le encanta esto porque sabe qué es el objeto $session.
$session = session(); 
?>

<?= view('templates/header') ?>

    <div style="margin-top: 100px">
        <div class="card">
            <!-- 2. Traemos los datos directo del objeto usando sus métodos orientados a objetos -->
            <h1>¡Hola, <?= esc(session()->get('name')) ?> <?= esc(session()->get('last_name')) ?>!</h1>
            <p>Bienvenido a tu panel de usuario.</p>
            <hr>
            <p><strong>Tu Email:</strong> <?php echo $session->get('email'); ?></p>
            <p><strong>Tu Rol actual:</strong> <span class="badge"><?php echo $session->get('role'); ?></span></p>
            
            <a href="/logout">Cerrar Sesión</a>
        </div>
    </div>

    <?= view('templates/footer') ?>
