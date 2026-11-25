<?= view('templates/header', ['titulo' => 'Agregar combo']) ?>

<div class="container" style="margin-top: 30px; max-width: 600px;">
    <h1 class="mb-4">Agregar combo</h1>

    <?php if (session()->getFlashdata('errores')): ?>
        <div class="alert alert-danger">
            <?php foreach ((array) session()->getFlashdata('errores') as $error): ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('combos/guardar') ?>" method="post" enctype="multipart/form-data" class="card card-body shadow-sm">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="comida" class="form-label">Comida</label>
            <input type="text" id="comida" name="comida" class="form-control"
                   maxlength="20" value="<?= esc(old('comida')) ?>" required>
        </div>

        <div class="mb-3">
            <label for="bebida" class="form-label">Bebida</label>
            <input type="text" id="bebida" name="bebida" class="form-control"
                   maxlength="20" value="<?= esc(old('bebida')) ?>" required>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" id="precio" name="precio" class="form-control"
                   min="0" step="0.01" value="<?= esc(old('precio')) ?>" required>
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen (opcional · JPG, PNG o WEBP, máx. 2 MB)</label>
            <input type="file" id="imagen" name="imagen" class="form-control"
                   accept="image/jpeg,image/png,image/webp">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Guardar combo</button>
            <a href="<?= base_url('combos') ?>" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?= view('templates/footer') ?>
