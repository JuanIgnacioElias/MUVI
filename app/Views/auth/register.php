<?= view('templates/header') ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <!-- Tarjeta contenedora principal -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4 p-sm-5">
                    
                    <h3 class="card-title text-center mb-4 fw-bold text-primary">Registro de Usuario</h3>

                    <!-- Alerta de errores -->
                    <?php if (session()->getFlashdata('errores')): ?>
                        <div class="alert alert-danger rounded-3 small">
                            <ul class="mb-0 ps-3">
                                <?php foreach (session()->getFlashdata('errores') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Formulario -->
                    <form action="<?= base_url('register') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold text-secondary">Nombre</label>
                            <input type="text" name="name" id="name" class="form-control" value="<?= esc(old('name')) ?>" placeholder="Ingresá tu nombre" required>
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label fw-semibold text-secondary">Apellido</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" value="<?= esc(old('last_name')) ?>" placeholder="Ingresá tu apellido" required>
                        </div>

                        <div class="mb-3">
                            <label for="cuil" class="form-label fw-semibold text-secondary">CUIL (11 dígitos)</label>
                            <input type="text" name="cuil" id="cuil" class="form-control" inputmode="numeric" pattern="[0-9]{11}" maxlength="11" value="<?= esc(old('cuil')) ?>" placeholder="Ej: 20123456789" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold text-secondary">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?= old('email') ?>" placeholder="nombre@correo.com" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold text-secondary">Contraseña</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3 shadow-sm">Registrarse</button>
                    </form>

                    <!-- Enlace de redirección -->
                    <div class="text-center mt-3">
                        <a href="<?= base_url('login') ?>" class="text-decoration-none small text-muted">
                            ¿Ya tenés una cuenta? <span class="text-primary fw-semibold">Iniciá sesión</span>
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?= view('templates/footer') ?>
