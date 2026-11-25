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
                <?php $idCombo = (int) $combo['id_combo']; ?>
                <div class="combo-card">
                    <?php if (! empty($combo['imagen'])): ?>
                        <img src="<?= esc(base_url($combo['imagen']), 'attr') ?>"
                             alt="Imagen del combo" class="combo-foto">
                    <?php endif; ?>

                    <div class="combo-info">
                        <!-- Comida --> 
                        <div class="combo-item">
                            <p> <?= esc($combo['comida']) ?></p>
                        </div>

                        <!-- Bebida -->
                        <div class="combo-item">
                            <p> <?= esc($combo['bebida']) ?></p>
                        </div>
                    </div>

                    <!-- Precio -->
                    <div class="combo-price">
                        $<?= number_format($combo['precio'], 0, ',', '.') ?>
                    </div>

                    <!-- Selector de Cantidad -->
                    <div class="combo-cantidad" style="display: flex; align-items: center; gap: 6px; margin-top: 10px;">
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-menos">−</button>
                        <input type="number" 
                               name="cantidades[<?= $idCombo ?>]" 
                               value="0" 
                               min="0" 
                               max="20"
                               data-precio="<?= (float) $combo['precio'] ?>" 
                               class="combo-qty form-control form-control-sm text-center" 
                               style="width: 55px;" 
                               readonly>
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-mas">+</button>
                    </div>
                </div>
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
            <div style="display: flex; gap: 10px;">
                <a href="<?= base_url('combos/omitir') ?>" class="btn btn-outline-secondary">Continuar sin combos</a>
                <button type="submit" id="btn-continuar" class="btn-continuar" disabled>Continuar</button>
            </div>
        </div>
    <?php endif; ?>
</form>

<script>
    (function () {
        const cards = document.querySelectorAll('.combo-card');
        const total = document.getElementById('total');
        const cant  = document.getElementById('cant');
        const btn   = document.getElementById('btn-continuar');

        function actualizar() {
            let suma = 0, n = 0;
            cards.forEach(function (card) {
                const input = card.querySelector('.combo-qty');
                const cantidad = parseInt(input.value) || 0;
                const precio   = parseFloat(input.dataset.precio) || 0;

                card.classList.toggle('seleccionado', cantidad > 0);
                if (cantidad > 0) {
                    suma += precio * cantidad;
                    n += cantidad;
                }
            });

            if (total) total.textContent = suma.toLocaleString('es-AR', { maximumFractionDigits: 0 });
            if (cant)  cant.textContent  = n;
            if (btn)   btn.disabled      = (n === 0);
        }

        cards.forEach(function (card) {
            const input = card.querySelector('.combo-qty');
            card.querySelector('.btn-menos').addEventListener('click', function () {
                let val = parseInt(input.value) || 0;
                if (val > 0) { input.value = val - 1; actualizar(); }
            });
            card.querySelector('.btn-mas').addEventListener('click', function () {
                let val = parseInt(input.value) || 0;
                if (val < 20) { input.value = val + 1; actualizar(); }
            });
        });

        actualizar();
    })();
</script>
