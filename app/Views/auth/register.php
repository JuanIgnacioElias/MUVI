<?= view('templates/header') ?>

<div>
    <div>
        <div>
            <h3>Registro de Usuario</h3>

            <?php if (session()->getFlashdata('errores')): ?>
                <div class="alert alert-danger">
                    <ul>
                    <?php foreach (session()->getFlashdata('errores') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- 1. Crear un formulario con método POST que envíe los datos a la ruta 'register' -->
            <form action="<?= base_url('register') ?>" method="post">
                <div>
                    <!-- 1.1 Insertar un campo para ingresar el nombre completo -->
                    <label for="name">Nombre:</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="<?= old('name') ?>"
                        required
                    >
                </div>
                <div>
                    <!-- 1.2 Insertar un campo para ingresar el email -->
                    <label for="email">Email:</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="<?= old('email') ?>"
                        required
                    >
                </div>
                <div>
                    <!-- 1.3 Insertar un campo para ingresar la contraseña -->
                    <label for="password">Contraseña:</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required
                    >
                </div>
                <!-- 1.4 Añadir un botón para enviar el formulario -->
                    <button type="submit">Registrarse</button>
            </form>
            <div>
                <!-- 2. Añadir un enlace para redirigir a 'login' si el usuario ya tiene cuenta -->
                <a href="<?= base_url('login') ?>">
                    ¿Ya tenés una cuenta? Iniciá sesión
                </a>
            </div>
        </div>
    </div>
</div>

<?= view('templates/footer') ?>
