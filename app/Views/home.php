<?= view('templates/header') ?>
<div class="row my-5">
    <div class="col text-center">
        <?php if (!session()->get('isLoggedIn')): ?>
            <h2 class="text-muted">Bienvenido a la plataforma</h2>
            <p class="text-secondary">Iniciá sesión o registrate desde el menú superior para comenzar.</p>
            <?php else: ?>
                <h2 class="text-muted"> ¡Bienvenido, <?= esc(session()->get('name')) ?>! </h2>
                <p class="text-secondary"> Nos alegra tenerte de nuevo en M.U.V.I. Ya podés explorar nuestra plataforma. </p>
        <?php endif; ?>
    </div>
</div>
<div class="seccion-cartelera">
<div class="banner-muvi">
    <img src="<?= base_url('assets/img/Muvi Diseño Web.png') ?>" alt="MUVI Cartelera">
  </div>
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

</div>
<a  href= <?=site_url('compras')?>>compras</a>
<a  href= <?=site_url('pago')?>>Pago</a>
<a href= <?=site_url('butaca')?>>seleccion de butaca</a>
<?= view('templates/footer') ?>