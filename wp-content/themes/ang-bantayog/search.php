<?php
/**
 * Search results template.
 */
get_header();
?>

<main class="container section-block">
  <div class="section-heading">
    <h2><?php printf( esc_html__( 'Resulta ng Paghahanap: %s', 'ang-bantayog' ), '&ldquo;' . get_search_query() . '&rdquo;' ); ?></h2>
  </div>

  <div class="cards-grid">
    <?php if ( have_posts() ) : $i = 0; while ( have_posts() ) : the_post(); ?>
      <article class="news-card article-card">
        <a href="<?php the_permalink(); ?>"><?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-wide', $i, get_the_title() ); ?></a>
        <span class="badge"><?php echo angbantayog_badge_label( get_the_ID() ); ?></span>
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
        <div class="meta"><?php echo esc_html( get_the_date() ); ?></div>
      </article>
    <?php $i++; endwhile; else : ?>
      <p><?php esc_html_e( 'Walang nahanap na artikulo. Subukan ang ibang keyword.', 'ang-bantayog' ); ?></p>
    <?php endif; ?>
  </div>

  <?php the_posts_pagination(); ?>
</main>

<?php get_footer(); ?>
