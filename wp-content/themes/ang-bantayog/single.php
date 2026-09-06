<?php
/**
 * Single post template — matches the article.html layout from the
 * original static prototype.
 */
get_header();
?>

<main class="article-layout">
  <?php while ( have_posts() ) : the_post(); ?>
  <article <?php post_class( 'article-page' ); ?> id="post-<?php the_ID(); ?>">
    <div class="article-hero">
      <?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-featured', 0, get_the_title() ); ?>
    </div>
    <div class="article-content">
      <span class="badge"><?php echo angbantayog_badge_label( get_the_ID() ); ?></span>
      <h1><?php the_title(); ?></h1>
      <p class="meta">Ni <?php the_author(); ?> · <?php echo esc_html( get_the_date() ); ?> · <?php echo angbantayog_reading_time( get_the_ID() ); ?></p>

      <?php if ( has_excerpt() ) : ?>
        <p class="lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
      <?php endif; ?>

      <?php the_content(); ?>

      <?php
      wp_link_pages( array(
        'before' => '<div class="page-links">' . __( 'Pages:', 'ang-bantayog' ),
        'after'  => '</div>',
      ) );
      ?>
    </div>
  </article>

  <?php if ( comments_open() || get_comments_number() ) : ?>
    <div class="article-content">
      <?php comments_template(); ?>
    </div>
  <?php endif; ?>

  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
