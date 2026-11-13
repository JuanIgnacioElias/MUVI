<?php
/**
 * Formulario de alta y edición de funciones.
 *
 * @var array|null $funcion    null = nueva función, array = edición
 * @var array      $salas      [id_sala, nombre]
 * @var array      $peliculas  [id_pelicula, titulo]
 */
$esEdicion = ! empty($funcion);
$accion    = $esEdicion
    ? site_url('funcion/update/' . $funcion['id_funcion'])
    : site_url('funcion/store');
$errores   = (array) (session()->getFlashdata('errors') ?? []);

// Valor del campo: lo que se acaba de enviar (si hubo error) o el guardado.
$valor = static fn (string $campo) => old($campo, $funcion[$campo] ?? '');
?>
<?= view('templates/header', [
    'titulo' => $esEdicion ? 'Editar función' : 'Nueva función',
]) ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <h2 class="mb-4">
            <?= $esEdicion ? 'Editar función #' . esc($funcion['id_funcion']) : 'Nueva función' ?>
        </h2>

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
                <form action="<?= $accion ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" id="fecha" name="fecha" class="form-control"
                               value="<?= $valor('fecha') ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="hora_ingreso" class="form-label">Hora de ingreso</label>
                            <input type="time" id="hora_ingreso" name="hora_ingreso" class="form-control"
                                   value="<?= substr((string) $valor('hora_ingreso'), 0, 5) ?>" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="hora_salida" class="form-label">Hora de salida</label>
                            <input type="time" id="hora_salida" name="hora_salida" class="form-control"
                                   value="<?= substr((string) $valor('hora_salida'), 0, 5) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="id_pelicula" class="form-label">Película</label>
                        <select id="id_pelicula" name="id_pelicula" class="form-select" required>
                            <option value="">Elegí una película</option>
                            <?php foreach ($peliculas as $pelicula): ?>
                                <option value="<?= esc($pelicula['id_pelicula']) ?>"
                                    <?= (string) $valor('id_pelicula') === (string) $pelicula['id_pelicula'] ? 'selected' : '' ?>>
                                    <?= esc($pelicula['titulo']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="id_sala" class="form-label">Sala</label>
                        <select id="id_sala" name="id_sala" class="form-select" required>
                            <option value="">Elegí una sala</option>
                            <?php foreach ($salas as $sala): ?>
                                <option value="<?= esc($sala['id_sala']) ?>"
                                    <?= (string) $valor('id_sala') === (string) $sala['id_sala'] ? 'selected' : '' ?>>
                                    <?= esc($sala['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <?= $esEdicion ? 'Guardar cambios' : 'Crear función' ?>
                        </button>
                        <a href="<?= site_url('funcion') ?>" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= view('templates/footer') ?>
