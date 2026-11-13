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
                            <img src="<?= base_url('assets/images/' . esc($combo['comida']) . '.png') ?>" alt="Comida" class="combo-img" onerror="this.style.display='none';">
                        </div>

                        <!-- Bebida -->
                        <div class="combo-item">
                            <p>🥤 <?= esc($combo['bebida']) ?></p>
                            <img src="<?= base_url('assets/images/' . esc($combo['bebida']) . '.png') ?>" alt="Bebida" class="combo-img" onerror="this.style.display='none';">
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

<style>
    body { background-color: #0b0202; font-family: Arial, sans-serif; padding: 20px; color: white; }
    .combos-header { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto 20px; }
    .btn-admin { background: #b11e1e; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold; }
    .btn-admin:hover { background: #d12a2a; }
    .alerta { max-width: 1200px; margin: 0 auto 15px; padding: 10px 15px; border-radius: 4px; }
    .alerta.ok { background: #14532d; }
    .alerta.error { background: #7f1d1d; }
    .combos-container { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; max-width: 1200px; margin: 0 auto; }
    .combo-card { background-color: #3d0c0c; border: 2px solid #5a1414; border-radius: 4px; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; gap: 15px; cursor: pointer; color: white; }
    .combo-card.seleccionado { border-color: #ff4d4d; background-color: #5a1414; }
    .combo-check { width: 22px; height: 22px; accent-color: #ff4d4d; cursor: pointer; flex-shrink: 0; }
    .combo-info { display: flex; gap: 20px; flex-grow: 1; justify-content: center; }
    .combo-item { text-align: center; }
    .combo-img { max-height: 60px; display: block; margin-top: 5px; }
    .combo-price { font-size: 1.6rem; font-weight: 900; }
    .no-combos { color: #8c8c8c; text-align: center; grid-column: span 2; font-size: 1.2rem; }
    .combos-footer { max-width: 1200px; margin: 25px auto 0; display: flex; justify-content: space-between; align-items: center; }
    .combos-total { font-size: 1.3rem; }
    .btn-continuar { background: #b11e1e; color: white; border: 0; padding: 10px 28px; font-size: 1.1rem; font-weight: bold; border-radius: 4px; cursor: pointer; }
    .btn-continuar:disabled { background: #444; color: #999; cursor: not-allowed; }
</style>
