<?= view('templates/header') ?>

<div class="seccion-cartelera">
    <div class="banner-muvi">
        <!-- Imagen de fondo -->
        <img src="<?= base_url('assets/img/Muvi Diseño Web.png') ?>" alt="MUVI Cartelera" class="banner-img">
        
        <!-- Texto superpuesto -->
        <div class="banner-texto">
            <?php if (!session()->get('isLoggedIn')): ?>
                <h2>Bienvenido a la plataforma</h2>
                <p>Iniciá sesión o registrate desde el menú superior para comenzar.</p>
            <?php else: ?>
                <h2>¡Bienvenido, <?= esc(session()->get('name')) ?>!</h2>
                <p>Nos alegra tenerte de nuevo en M.U.V.I. Ya podés explorar nuestra plataforma.</p>
            <?php endif; ?>
        </div>
    </div>
  <?php if (! empty($peliculas)): ?>
  <!-- Películas cargadas en la base de datos: cada una lleva a su página de detalle -->
  <section class="contenedor-peliculas">
    <?php foreach ($peliculas as $pelicula): ?>
    <article class="tarjeta-pelicula">
      <a href="<?= site_url('peliculas/' . (int) $pelicula['id_pelicula']) ?>" class="text-decoration-none text-dark w-100">
        <div class="poster">
          <?php if (! empty($pelicula['imagen'])): ?>
            <img src="<?= esc(base_url($pelicula['imagen']), 'attr') ?>" alt="<?= esc($pelicula['titulo'], 'attr') ?>">
          <?php endif; ?>
        </div>
        <h3><?= esc($pelicula['titulo']) ?></h3>
      </a>
    </article>
    <?php endforeach; ?>
  </section>
  <p class="text-center mt-3"><a href="<?= site_url('peliculas') ?>">Ver todas las películas</a></p>
  <?php else: ?>
  <section class="contenedor-peliculas">
    <!-- Película 1 -->
    <article class="tarjeta-pelicula">
      <div class="poster">
        <img src=<?= base_url('assets/img/minecraft.png') ?> alt="A Minecraft Movie">
      </div>
      <h3>A Minecraft Movie</h3>
    </article>

    <!-- Película 2 -->
    <article class="tarjeta-pelicula">
      <div class="poster">
      <img src=<?= base_url('assets/img/scary movie.png') ?> alt="Scary Movie">
      </div>
      <h3>Scary Movie</h3>
    </article>

    <!-- Película 3 -->
    <article class="tarjeta-pelicula">
      <div class="poster">
      <img src=<?= base_url('assets/img/residen evil.png') ?> alt="Resident Evil: Noche Cero">
      </div>
      <h3>Resident Evil: Noche Cero</h3>
    </article>

    <!-- Película 4 -->
    <article class="tarjeta-pelicula">
      <div class="poster">
      <img src=<?= base_url('assets/img/dun 3.png') ?> alt="Dune 3">
      </div>
      <h3>Dune 3</h3>
    </article>

    <!-- Película 5 -->
    <article class="tarjeta-pelicula">
      <div class="poster">
      <img src=<?= base_url('assets/img/coyote vs acme.png') ?> alt="Coyote vs ACME">
      </div>
      <h3>Coyote vs ACME</h3>
    </article>
  </section>
  <?php endif; ?>

</div>
<a  href= <?=site_url('compras')?>>compras</a>
<a href= <?=site_url('butaca')?>>seleccion de butaca</a>
<a href= <?=site_url('funcion')?>>funciones</a>
<?= view('templates/footer') ?>