<?php
/**
 * Formulario de alta y edición de películas (solo ADMINISTRADOR).
 *
 * @var array|null $pelicula    null = nueva, array = edición
 */
$esEdicion = ! empty($pelicula);
$accion    = $esEdicion
    ? site_url('admin/peliculas/actualizar/' . (int) $pelicula['id_pelicula'])
    : site_url('admin/peliculas/guardar');
$errores   = (array) (session()->getFlashdata('errors') ?? []);

// Lo que se acaba de enviar (si hubo error) o lo guardado, SIN escapar:
// el escape se hace una sola vez al imprimir (old() escapa por defecto).
$valor = static fn (string $campo): string => (string) (old($campo, null, false) ?? ($pelicula[$campo] ?? ''));
?>
<?= view('templates/header', ['titulo' => $esEdicion ? 'Editar película' : 'Agregar película']) ?>

<div class="row justify-content-center">
    <div class="col-md-9 col-lg-7">
        <h1 class="h2 mb-4"><?= $esEdicion ? 'Editar película' : 'Agregar película' ?></h1>

        <?php if (! empty($errores)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errores as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="<?= $accion ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" id="titulo" name="titulo" class="form-control" maxlength="40"
                               value="<?= esc($valor('titulo'), 'attr') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="sinopsis" class="form-label">Sinopsis</label>
                        <textarea id="sinopsis" name="sinopsis" class="form-control" rows="5"><?= esc($valor('sinopsis')) ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="duracion" class="form-label">Duración (minutos)</label>
                            <input type="number" id="duracion" name="duracion" class="form-control" min="1" step="1"
                                   value="<?= esc($valor('duracion'), 'attr') ?>" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="clasificacion" class="form-label">Clasificación</label>
                            <input type="text" id="clasificacion" name="clasificacion" class="form-control" maxlength="40"
                                   placeholder="Ej.: ATP, +13, +16" value="<?= esc($valor('clasificacion'), 'attr') ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="categoria" class="form-label">Categoría</label>
                            <input type="text" id="categoria" name="categoria" class="form-control" maxlength="40"
                                   placeholder="Ej.: Acción, Comedia, Terror"
                                   value="<?= esc($valor('categoria'), 'attr') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <?php $estadoActual = $valor('estado') ?: \App\Models\PeliculaModel::ESTRENO; ?>
                            <select id="estado" name="estado" class="form-select" required>
                                <?php foreach (\App\Models\PeliculaModel::ESTADOS as $clave => $texto): ?>
                                    <option value="<?= esc($clave, 'attr') ?>" <?= $estadoActual === $clave ? 'selected' : '' ?>>
                                        <?= esc($texto) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="trailer_url" class="form-label">Tráiler (URL, opcional)</label>
                        <input type="url" id="trailer_url" name="trailer_url" class="form-control"
                               placeholder="https://www.youtube.com/watch?v=..."
                               value="<?= esc($valor('trailer_url'), 'attr') ?>">
                    </div>

                    <div class="mb-4">
                        <label for="imagen" class="form-label">Póster (JPG, PNG o WEBP, máx. 2 MB)</label>
                        <?php if ($esEdicion && ! empty($pelicula['imagen'])): ?>
                            <div class="mb-2">
                                <img src="<?= esc(base_url($pelicula['imagen']), 'attr') ?>" alt="Póster actual"
                                     height="150" class="rounded border">
                                <div class="form-text">Póster actual. Si subís otro, lo reemplaza.</div>
                            </div>
                        <?php endif; ?>
                        <input type="file" id="imagen" name="imagen" class="form-control"
                               accept="image/jpeg,image/png,image/webp">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <?= $esEdicion ? 'Guardar cambios' : 'Agregar película' ?>
                        </button>
                        <a href="<?= site_url('admin/peliculas') ?>" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= view('templates/footer') ?>
