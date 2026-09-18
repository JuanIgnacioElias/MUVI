<?= view('templates/header') ?>

<div>
    <div>
        <div>
            <h3>Iniciar Sesión</h3>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('exito')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('exito') ?></div>
            <?php endif; ?>

            <!-- 1. Crear un formulario con método POST que envíe los datos a la ruta 'login' -->
            <form action="<?= base_url('login') ?>" method="post">
                <div>
                    <!-- 1.1 Insertar un campo para el mail -->
                    <label for="email">Correo electrónico:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div>
                    <!-- 1.2 Insertar un campo para la contraseña -->
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                    <!-- 1.3 Añadir un botón para enviar el formulario -->
                    <button type="submit">Iniciar sesión</button>
            </form>
            <div>
                <!-- 2. Añadir un enlace para redirigir a 'register' si el usuario no tiene cuenta -->
                <a href="<?= base_url('register') ?>">¿No tenés una cuenta? Registrate</a>
            </div>
        </div>
    </div>
</div>

<?= view('templates/footer') ?>