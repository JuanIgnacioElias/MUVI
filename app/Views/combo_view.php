<?= view('templates/header') ?>
<div>
<div class="combos-container">
    <?php if (!empty($combos) && is_array($combos)): ?>
        <?php foreach ($combos as $combo): ?>
            <!-- Tarjeta individual de combo (Estilo Borravino) -->
            <div class="combo-card" onclick="seleccionarCombo(<?= $combo['id_combo'] ?>, '<?= esc($combo['comida']) ?>', <?= $combo['precio'] ?>)">
                
                <div class="combo-info">
                    <!-- Icono o Identificador de Comida -->
                    <span class="combo-item comida-icon"><?= esc($combo['comida']) ?></span>
                    <!-- Icono o Identificador de Bebida -->
                    <span class="combo-item bebida-icon"><?= esc($combo['bebida']) ?></span>
                </div>

                <!-- Bloque de Precio alineado a la derecha -->
                <div class="combo-price">
                    $<?= number_format($combo['precio'], 0, ',', '.') ?>
                </div>

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-combos">No hay combos disponibles en este momento.</p>
    <?php endif; ?>
</div>

<style>
    /* Estilos base alineados al diseño de la imagen */
    body {
        background-color: #0b0202; /* Fondo ultra oscuro del sitio */
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 20px;
    }

    .combos-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr); /* 2 columnas idénticas */
        gap: 15px; /* Espaciado entre combos */
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Tarjeta que representa cada fila borravino de la captura */
    .combo-card {
        background-color: #3d0c0c; /* Color borravino oscuro */
        border: 2px solid #5a1414;
        border-radius: 4px;
        padding: 15px 25px;
        display: flex;
        justify-content: space-between; /* Separa los iconos a la izquierda y el precio a la derecha */
        align-items: center;
        cursor: pointer;
        transition: transform 0.2s, background-color 0.2s;
    }

    /* Efecto hover interactivo al pasar el mouse */
    .combo-card:hover {
        background-color: #4d1111;
        transform: scale(1.01);
    }

    /* Contenedor de iconos/textos de comida y bebida */
    .combo-info {
        display: flex;
        gap: 20px;
        align-items: center;
    }

    .combo-item {
        color: #ffffff;
        font-size: 1.1rem;
        font-weight: bold;
    }

    /* Estilo para el precio */
    .combo-price {
        color: #ffffff;
        font-size: 1.6rem;
        font-weight: 900;
        letter-spacing: 0.5px;
    }

    .no-combos {
        color: #8c8c8c;
        grid-column: span 2;
        text-align: center;
    }

    /* Adaptabilidad para pantallas de celulares pequeños */
    @media (max-width: 768px) {
        .combos-container {
            grid-template-columns: 1fr; /* Pasa a 1 sola columna en móviles */
        }
    }
</style>

<script>
    // Función JavaScript para manejar la selección del combo al hacer clic
    function seleccionarCombo(id, nombre, precio) {
        console.log("Combo seleccionado:", id, nombre, precio);
        // Aquí puedes escribir la lógica para añadirlo a un carrito o procesar el pedido
    }
</script>
</div>
<?= view('templates/footer') ?>