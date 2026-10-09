<?= view('templates/header') ?>

<div class="row my-5">
    <div class="col text-center">
        <?php if (!session()->get('isLoggedIn')): ?>
            <h2 class="text-muted">Bienvenido a la plataforma</h2>
            <p class="text-secondary">Iniciá sesión o registrate desde el menú superior para comenzar.</p>
            <?php else: ?>
                <h2 class="text-muted"> ¡Bienvenido, <?= esc(session()->get('name')) ?>! </h2>
                <p class="text-secondary"> Nos alegra tenerte de nuevo en M.U.V.I. Ya podés explorar nuestra plataforma. </p>
        <?php endif; ?>
    </div>
</div>

<?= view('templates/footer') ?>