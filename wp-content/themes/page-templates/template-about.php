<?php
/**
 * Template Name: Tungkol Sa Amin (About)
 * Description: Reproduces the about.html layout, including the
 * Misyon / Bisyon / Haligi ng Publikasyon info grid.
 */
get_header();
?>

<main class="container page-content">
  <?php while ( have_posts() ) : the_post(); ?>
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
  <?php endwhile; ?>

  <section class="info-grid">
    <article>
      <h2>Misyon</h2>
      <p>Magbigay ng tapat, malinaw, at makabuluhang pamamahayag para sa mag-aaral, guro, at komunidad.</p>
    </article>
    <article>
      <h2>Bisyon</h2>
      <p>Maging isang modernong campus publication na nagsusulong ng katotohanan, kritikal na pag-iisip, at tagumpay ng kabataan.</p>
    </article>
    <article>
      <h2>Haligi ng Publikasyon</h2>
      <p>Katotohanan, pananagutan, husay sa pagsulat, respeto, at serbisyo sa komunidad.</p>
    </article>
  </section>
</main>

<?php get_footer(); ?>
