<div class="combos-container">
    <?php if (!empty($combos) && is_array($combos)): ?>
        <?php foreach ($combos as $combo): ?>
            <div class="combo-card" onclick="seleccionarCombo(<?= $combo['id_combo'] ?>, <?= $combo['precio'] ?>)">
                
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

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-combos">No hay combos disponibles en la base de datos.</p>
    <?php endif; ?>
</div>

<style>
    body { background-color: #0b0202; font-family: Arial, sans-serif; padding: 20px; }
    .combos-container { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; max-width: 1200px; margin: 0 auto; }
    .combo-card { background-color: #3d0c0c; border: 2px solid #5a1414; border-radius: 4px; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; cursor: pointer; color: white; }
    .combo-info { display: flex; gap: 20px; }
    .combo-item { text-align: center; }
    .combo-img { max-height: 60px; display: block; margin-top: 5px; }
    .combo-price { font-size: 1.6rem; font-weight: 900; }
    .no-combos { color: #8c8c8c; text-align: center; grid-column: span 2; font-size: 1.2rem; }
</style>
