<?php
/**
 * Catálogo público de películas, separado por estado.
 *
 * @var array $enEstreno     películas actualmente en estreno
 * @var array $proximamente  películas que se estrenan próximamente
 */
$esAdmin = strtoupper((string) session()->get('role')) === 'ADMINISTRADOR';

$secciones = [
    [
        'titulo'       => '🎟️ En estreno',
        'peliculas'    => $enEstreno,
        'vacio'        => 'No hay películas en estreno por el momento.',
        'proximamente' => false,
    ],
    [
        'titulo'       => '⏳ Próximamente',
        'peliculas'    => $proximamente,
        'vacio'        => 'Todavía no hay estrenos anunciados.',
        'proximamente' => true,
    ],
];
?>
<?= view('templates/header', ['titulo' => 'Películas']) ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h2 mb-0">🎬 Películas</h1>
    <?php if ($esAdmin): ?>
        <a href="<?= site_url('admin/peliculas') ?>" class="btn btn-outline-dark">Administrar películas</a>
    <?php endif; ?>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<?php foreach ($secciones as $seccion): ?>
    <section class="mb-5">
        <h2 class="h3 mb-3"><?= esc($seccion['titulo']) ?></h2>

        <?php if (! empty($seccion['peliculas'])): ?>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
                <?php foreach ($seccion['peliculas'] as $pelicula): ?>
                    <div class="col">
                        <a href="<?= site_url('peliculas/' . (int) $pelicula['id_pelicula']) ?>"
                           class="text-decoration-none text-dark">
                            <div class="card h-100 shadow-sm">
                                <div class="ratio bg-secondary-subtle" style="--bs-aspect-ratio: 150%;">
                                    <?php if (! empty($pelicula['imagen'])): ?>
                                        <img src="<?= esc(base_url($pelicula['imagen']), 'attr') ?>"
                                             alt="Póster de <?= esc($pelicula['titulo'], 'attr') ?>"
                                             class="card-img-top" style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center fs-1">🎬</div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body text-center">
                                    <h3 class="h6 fw-bold mb-2"><?= esc($pelicula['titulo']) ?></h3>
                                    <?php if ($seccion['proximamente']): ?>
                                        <span class="badge text-bg-warning">Próximamente</span>
                                    <?php endif; ?>
                                    <?php if (! empty($pelicula['categoria'])): ?>
                                        <span class="badge text-bg-dark"><?= esc($pelicula['categoria']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted py-3"><?= esc($seccion['vacio']) ?></p>
        <?php endif; ?>
    </section>
<?php endforeach; ?>

<?= view('templates/footer') ?>
