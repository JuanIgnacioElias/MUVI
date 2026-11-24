<?php
/**
 * Detalle de una película y sus funciones programadas.
 *
 * @var array $pelicula           película con la columna "categoria"
 * @var array $funcionesPorFecha  ['YYYY-MM-DD' => [funcion, ...]] (desde hoy)
 */
$rol        = strtoupper((string) session()->get('role'));
$logueado   = (bool) session()->get('isLoggedIn');
$esCliente  = $rol === 'CLIENTE';
$esAdmin    = $rol === 'ADMINISTRADOR';
$dias       = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
?>
<?= view('templates/header', ['titulo' => $pelicula['titulo']]) ?>

<nav aria-label="breadcrumb">
    <a href="<?= site_url('peliculas') ?>" class="text-decoration-none">&larr; Todas las películas</a>
</nav>

<div class="row g-4 mt-1 mb-5">
    <div class="col-md-4 col-lg-3">
        <div class="ratio bg-secondary-subtle rounded shadow-sm overflow-hidden" style="--bs-aspect-ratio: 150%;">
            <?php if (! empty($pelicula['imagen'])): ?>
                <img src="<?= esc(base_url($pelicula['imagen']), 'attr') ?>"
                     alt="Póster de <?= esc($pelicula['titulo'], 'attr') ?>" style="object-fit: cover;">
            <?php else: ?>
                <div class="d-flex align-items-center justify-content-center fs-1">🎬</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-8 col-lg-9">
        <h1 class="mb-3"><?= esc($pelicula['titulo']) ?></h1>

        <div class="d-flex flex-wrap gap-2 mb-3">
            <?php if (! empty($pelicula['categoria'])): ?>
                <a href="<?= site_url('peliculas') . '?categoria=' . (int) $pelicula['id_categoria'] ?>"
                   class="badge text-bg-dark text-decoration-none"><?= esc($pelicula['categoria']) ?></a>
            <?php endif; ?>
            <span class="badge text-bg-secondary">Clasificación: <?= esc($pelicula['clasificacion']) ?></span>
            <span class="badge text-bg-secondary"><?= (int) $pelicula['duracion'] ?> min</span>
        </div>

        <?php if (! empty($pelicula['sinopsis'])): ?>
            <h2 class="h5">Sinopsis</h2>
            <p style="white-space: pre-line;"><?= esc($pelicula['sinopsis']) ?></p>
        <?php endif; ?>

        <div class="d-flex flex-wrap gap-2 mt-3">
            <?php if (! empty($pelicula['trailer_url'])): ?>
                <a href="<?= esc($pelicula['trailer_url'], 'attr') ?>" target="_blank" rel="noopener noreferrer"
                   class="btn btn-outline-dark">▶ Ver tráiler</a>
            <?php endif; ?>
            <?php if ($esAdmin): ?>
                <a href="<?= site_url('admin/peliculas/editar/' . (int) $pelicula['id_pelicula']) ?>"
                   class="btn btn-warning">Editar película</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<section id="funciones" class="mb-5">
    <h2 class="h3 mb-3">Funciones disponibles</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (! $logueado && ! empty($funcionesPorFecha)): ?>
        <div class="alert alert-info">
            <a href="<?= site_url('login') ?>" class="alert-link">Iniciá sesión</a> para elegir una función y comprar tus entradas.
        </div>
    <?php endif; ?>

    <?php if (! empty($funcionesPorFecha)): ?>
        <?php foreach ($funcionesPorFecha as $fecha => $funciones): ?>
            <?php $ts = strtotime($fecha); ?>
            <h3 class="h6 text-uppercase text-muted mt-4 mb-2">
                <?= esc($dias[(int) date('w', $ts)]) ?> <?= date('d/m/Y', $ts) ?>
            </h3>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                <?php foreach ($funciones as $funcion): ?>
                    <div class="col">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold fs-5">
                                        <?= esc(substr($funcion['hora_ingreso'], 0, 5)) ?>
                                        <span class="text-muted fs-6">a <?= esc(substr($funcion['hora_salida'], 0, 5)) ?></span>
                                    </div>
                                    <div class="text-muted small">
                                        <?= esc($funcion['sala'] ?? 'Sala a confirmar') ?>
                                    </div>
                                </div>

                                <?php if ($esCliente): ?>
                                    <a href="<?= site_url('funcion/seleccionar/' . (int) $funcion['id_funcion']) ?>"
                                       class="btn btn-info text-white fw-bold">Seleccionar</a>
                                <?php elseif (! $logueado): ?>
                                    <a href="<?= site_url('login') ?>" class="btn btn-outline-secondary btn-sm">Ingresar</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted">Esta película todavía no tiene funciones programadas.</p>
    <?php endif; ?>
</section>

<?= view('templates/footer') ?>
