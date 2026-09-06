<?php
/**
 * Generic page template — matches the about.html "page-content" layout
 * from the original static prototype.
 */
get_header();
?>

<main class="container page-content">
  <?php while ( have_posts() ) : the_post(); ?>
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
