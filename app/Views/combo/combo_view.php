
<link rel="stylesheet" href="<?= base_url('assets/css/combo.css') ?>">
<?php $esAdmin = strtoupper((string) session()->get('role')) === 'ADMINISTRADOR'; ?>

<div class="combos-header">
    <h1>Combos</h1>
    <?php if ($esAdmin): ?>
        <a href="<?= base_url('combos/nuevo') ?>" class="btn-admin">+ Agregar combo</a>
    <?php endif; ?>
</div>

<?php if (session()->getFlashdata('mensaje')): ?>
    <div class="alerta ok"><?= esc(session()->getFlashdata('mensaje')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('errores')): ?>
    <div class="alerta error">
        <?php foreach ((array) session()->getFlashdata('errores') as $error): ?>
            <div><?= esc($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form action="<?= base_url('combos/seleccionar') ?>" method="post" id="form-combos">
    <?= csrf_field() ?>

    <div class="combos-container">
        <?php if (!empty($combos) && is_array($combos)): ?>
            <?php foreach ($combos as $combo): ?>
                <label class="combo-card">
                    <input type="checkbox" name="combos[]" value="<?= (int) $combo['id_combo'] ?>"
                           data-precio="<?= (float) $combo['precio'] ?>" class="combo-check">

                    <div class="combo-info">
                        <!-- Comida -->
                        <div class="combo-item">
                            <p>🍿 <?= esc($combo['comida']) ?></p>
                        </div>

                        <!-- Bebida -->
                        <div class="combo-item">
                            <p>🥤 <?= esc($combo['bebida']) ?></p>
                        </div>
                    </div>

                    <!-- Precio -->
                    <div class="combo-price">
                        $<?= number_format($combo['precio'], 0, ',', '.') ?>
                    </div>
                </label>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-combos">No hay combos disponibles en la base de datos.</p>
        <?php endif; ?>
    </div>

    <?php if (!empty($combos)): ?>
        <div class="combos-footer">
            <div class="combos-total">
                Seleccionados: <span id="cant">0</span> &nbsp;|&nbsp; Total: <strong>$<span id="total">0</span></strong>
            </div>
            <button type="submit" id="btn-continuar" class="btn-continuar" disabled>Continuar</button>
        </div>
    <?php endif; ?>
</form>

<script>
    (function () {
        const checks = document.querySelectorAll('.combo-check');
        const total  = document.getElementById('total');
        const cant   = document.getElementById('cant');
        const btn    = document.getElementById('btn-continuar');

        function actualizar() {
            let suma = 0, n = 0;
            checks.forEach(function (c) {
                c.closest('.combo-card').classList.toggle('seleccionado', c.checked);
                if (c.checked) { suma += parseFloat(c.dataset.precio) || 0; n++; }
            });
            if (total) total.textContent = suma.toLocaleString('es-AR', { maximumFractionDigits: 0 });
            if (cant)  cant.textContent  = n;
            if (btn)   btn.disabled      = (n === 0);
        }

        checks.forEach(function (c) { c.addEventListener('change', actualizar); });
        actualizar();
    })();
</script>
