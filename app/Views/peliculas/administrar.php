<?php
/**
 * Listado de administración de películas (solo ADMINISTRADOR).
 *
 * @var array $peliculas
 */
?>
<?= view('templates/header', ['titulo' => 'Administrar películas']) ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 mb-0">Administrar películas</h1>
    <a href="<?= site_url('admin/peliculas/nueva') ?>" class="btn btn-primary">+ Agregar película</a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div class="card shadow-sm mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th style="width: 80px;">Póster</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Clasificación</th>
                    <th>Estado</th>
                    <th>Duración</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (! empty($peliculas)): ?>
                <?php foreach ($peliculas as $pelicula): ?>
                    <tr>
                        <td>
                            <?php if (! empty($pelicula['imagen'])): ?>
                                <img src="<?= esc(base_url($pelicula['imagen']), 'attr') ?>"
                                     alt="" width="50" height="75" style="object-fit: cover;" class="rounded">
                            <?php else: ?>
                                <span class="fs-3">🎬</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= site_url('peliculas/' . (int) $pelicula['id_pelicula']) ?>" class="fw-bold text-decoration-none">
                                <?= esc($pelicula['titulo']) ?>
                            </a>
                        </td>
                        <td><?= esc($pelicula['categoria'] ?? '—') ?></td>
                        <td><?= esc($pelicula['clasificacion']) ?></td>
                        <td>
                            <?php if (($pelicula['estado'] ?? '') === \App\Models\PeliculaModel::PROXIMAMENTE): ?>
                                <span class="badge text-bg-warning">Próximamente</span>
                            <?php else: ?>
                                <span class="badge text-bg-success">En estreno</span>
                            <?php endif; ?>
                        </td>
                        <td><?= (int) $pelicula['duracion'] ?> min</td>
                        <td class="text-center">
                            <a href="<?= site_url('admin/peliculas/editar/' . (int) $pelicula['id_pelicula']) ?>"
                               class="btn btn-warning btn-sm me-1">Editar</a>

                            <form action="<?= site_url('admin/peliculas/eliminar/' . (int) $pelicula['id_pelicula']) ?>"
                                  method="post" class="d-inline"
                                  onsubmit="return confirm('¿Seguro que querés eliminar esta película? También se eliminarán sus funciones.');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Todavía no hay películas cargadas.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('templates/footer') ?>
