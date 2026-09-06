<?php
/**
 * Template for ALL category archive pages
 * (Opinyon, Lathalain, Isports, Multimedia, etc.)
 */
get_header();

// Get the category object
$cat = get_queried_object();

// Safely get slug and name
$slug = isset($cat->slug) ? $cat->slug : '';
$name = isset($cat->name) ? $cat->name : single_cat_title('', false);

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

// Query for main content
$cat_posts = new WP_Query( array(
	'category_name'  => $slug,
	'posts_per_page' => 6,
	'paged'          => $paged,
	'post_status'    => 'publish'
) );

// Query for sidebar tabs
$bago    = new WP_Query( array( 
	'posts_per_page' => 4, 
	'category_name' => $slug, 
	'orderby' => 'date', 
	'no_found_rows' => true 
) );

$laganap = new WP_Query( array( 
	'posts_per_page' => 4, 
	'category_name' => $slug, 
	'orderby' => 'comment_count', 
	'no_found_rows' => true 
) );
?>

<main class="archive-page">

  <div class="container" style="margin-top: 24px;">
    <div class="balita-layout">

      <!-- Main: 3-column card grid -->
      <div class="balita-main">
        <div class="balita-card-row">
          <?php if ( $cat_posts->have_posts() ) : 
            while ( $cat_posts->have_posts() ) : $cat_posts->the_post(); ?>
            <article class="balita-card">
              <a class="balita-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                <?php the_post_thumbnail( 'angbantayog-wide', array( 'loading' => 'lazy' ) ); ?>
                <span class="tag tag--solid tag--pinned"><?php echo esc_html( $name ); ?></span>
              </a>
              <h3 class="balita-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p class="balita-card__meta">
                <?php echo esc_html( get_the_author() ); ?> &middot; <?php echo esc_html( get_the_date() ); ?> &middot; <?php echo esc_html( angbantayog_reading_time( get_the_ID() ) ); ?>
              </p>
              <div class="balita-card__excerpt">
                <?php echo wp_trim_words( get_the_excerpt(), 20, '&hellip;' ); ?>
              </div>
              <a class="balita-card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'ang-bantayog' ); ?></a>
            </article>
          <?php endwhile; wp_reset_postdata(); else : ?>
            <p class="empty"><?php esc_html_e( 'Wala pang artikulo sa seksyong ito.', 'ang-bantayog' ); ?></p>
          <?php endif; ?>
        </div>

        <?php if ( $cat_posts->max_num_pages > 1 ) : ?>
          <div class="pagination" style="margin: 24px 0;">
            <?php echo paginate_links( array( 'total' => $cat_posts->max_num_pages, 'current' => $paged ) ); ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Sidebar: promo + tabs -->
      <aside class="balita-sidebar">
        <div class="balita-promo">
          <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dspc-na.gif' ); ?>"
               alt="<?php esc_attr_e( 'DSPC Na!', 'ang-bantayog' ); ?>"
               loading="lazy" />
        </div>

        <div class="balita-tabs" id="balitaTabs">
          <div class="balita-tabs__bar">
            <button class="balita-tabs__btn is-active" data-tab="napapanahon">
              <?php esc_html_e( 'Napapanahon', 'ang-bantayog' ); ?>
            </button>
            <button class="balita-tabs__btn" data-tab="laganap">
              <?php esc_html_e( 'Laganap', 'ang-bantayog' ); ?>
            </button>
          </div>

          <div class="balita-tabs__panel is-active" id="tab-napapanahon">
            <?php if ( $bago->have_posts() ) : while ( $bago->have_posts() ) : $bago->the_post(); ?>
              <article class="balita-side-item">
                <a class="balita-side-item__media" href="<?php the_permalink(); ?>">
                  <?php the_post_thumbnail( 'thumbnail', array( 'loading' => 'lazy' ) ); ?>
                </a>
                <div class="balita-side-item__body">
                  <p class="eyebrow"><?php echo esc_html( implode( ', ', angbantayog_badge_labels( get_the_ID() ) ) ); ?></p>
                  <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                </div>
              </article>
            <?php endwhile; wp_reset_postdata(); else : ?>
              <p class="empty"><?php esc_html_e( 'Wala pang bagong artikulo.', 'ang-bantayog' ); ?></p>
            <?php endif; ?>
          </div>

          <div class="balita-tabs__panel" id="tab-laganap" hidden>
            <?php if ( $laganap->have_posts() ) : while ( $laganap->have_posts() ) : $laganap->the_post(); ?>
              <article class="balita-side-item">
                <a class="balita-side-item__media" href="<?php the_permalink(); ?>">
                  <?php the_post_thumbnail( 'thumbnail', array( 'loading' => 'lazy' ) ); ?>
                </a>
                <div class="balita-side-item__body">
                  <p class="eyebrow"><?php echo esc_html( implode( ', ', angbantayog_badge_labels( get_the_ID() ) ) ); ?></p>
                  <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                </div>
              </article>
            <?php endwhile; wp_reset_postdata(); else : ?>
              <p class="empty"><?php esc_html_e( 'Wala pang laganap na kuwento.', 'ang-bantayog' ); ?></p>
            <?php endif; ?>
          </div>
        </div>
      </aside>

    </div>
  </div>

</main>

<?php get_footer(); ?>