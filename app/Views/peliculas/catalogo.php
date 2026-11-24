<?php
/**
 * Catálogo público de películas.
 *
 * @var array $peliculas        [id_pelicula, titulo, imagen, categoria, ...]
 * @var array $categorias       [id_categoria, nombre]
 * @var int   $categoriaActiva  0 = todas
 */
$esAdmin = strtoupper((string) session()->get('role')) === 'ADMINISTRADOR';
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

<!-- Filtro por categoría -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="<?= site_url('peliculas') ?>"
       class="btn btn-sm <?= $categoriaActiva === 0 ? 'btn-dark' : 'btn-outline-dark' ?>">Todas</a>
    <?php foreach ($categorias as $categoria): ?>
        <a href="<?= site_url('peliculas') . '?categoria=' . (int) $categoria['id_categoria'] ?>"
           class="btn btn-sm <?= $categoriaActiva === (int) $categoria['id_categoria'] ? 'btn-dark' : 'btn-outline-dark' ?>">
            <?= esc($categoria['nombre']) ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if (! empty($peliculas)): ?>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4 mb-5">
        <?php foreach ($peliculas as $pelicula): ?>
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
                            <h2 class="h6 fw-bold mb-2"><?= esc($pelicula['titulo']) ?></h2>
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
    <p class="text-muted text-center py-5">No hay películas en esta categoría por el momento.</p>
<?php endif; ?>

<?= view('templates/footer') ?>
