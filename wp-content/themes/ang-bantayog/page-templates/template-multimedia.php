<?php
/**
 * Template Name: Multimedia
 *
 * A grouped landing page for the Multimedia section: breadcrumb, title,
 * then one block per sub-section (Podcast, Reels, and so on), with the
 * site's sidebar rail alongside.
 *
 * Grouping comes from CHILD CATEGORIES of "multimedia". Create them under
 * Posts > Categories with Multimedia as the parent — each child becomes a
 * block here, in the order WordPress sorts them. A post in the parent
 * category but no child lands in the catch-all block at the bottom.
 *
 * Setup: Pages > Add New > title "Multimedia" > Page Attributes >
 * Template > Multimedia > Publish, then point the nav item at that page.
 */

get_header();

$parent = get_category_by_slug( 'multimedia' );

$children = $parent ? get_categories( array(
	'parent'     => $parent->term_id,
	'hide_empty' => false,
	'orderby'    => 'name',
) ) : array();

$per_block = 3;
$shown     = array();

/** One block: heading plus a row of cards. */
function angbantayog_mm_block( $title, $query, $more_url = '', &$shown = null, $offset = 0 ) {
	if ( ! $query->have_posts() ) {
		return;
	}
	?>
	<section class="mm-block">
		<div class="band-head">
			<h2><?php echo esc_html( $title ); ?></h2>
			<?php if ( $more_url ) : ?>
				<a class="band-more" href="<?php echo esc_url( $more_url ); ?>"><?php esc_html_e( 'Lahat', 'ang-bantayog' ); ?></a>
			<?php endif; ?>
		</div>

		<div class="mm-grid">
			<?php $i = $offset; while ( $query->have_posts() ) : $query->the_post(); ?>
				<article class="mm-card">
					<a class="mm-card__media" href="<?php the_permalink(); ?>">
						<?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-wide', $i, get_the_title() ); ?>
						<span class="play play--sm" aria-hidden="true"></span>
					</a>
					<h3 class="mm-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p class="byline"><?php echo esc_html( get_the_author() ); ?> &middot; <?php echo esc_html( get_the_date() ); ?></p>
				</article>
			<?php
			if ( is_array( $shown ) ) {
				$shown[] = get_the_ID();
			}
			$i++;
			endwhile;
			?>
		</div>
	</section>
	<?php
	wp_reset_postdata();
}
?>

<main class="container page-multimedia">

  <nav class="crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'ang-bantayog' ); ?>">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'ang-bantayog' ); ?></a>
    <span aria-hidden="true">/</span>
    <span class="crumbs__here"><?php the_title(); ?></span>
  </nav>

  <header class="mm-head">
    <h1 class="mm-head__title"><?php the_title(); ?></h1>
    <?php
    while ( have_posts() ) : the_post();
      if ( trim( get_the_content() ) ) :
        ?><div class="mm-head__intro"><?php the_content(); ?></div><?php
      endif;
    endwhile;
    ?>
  </header>

  <div class="portal">

    <div class="portal__main">

      <?php
      $offset = 0;

      /* One block per child category. */
      foreach ( $children as $child ) {
        $q = new WP_Query( array(
          'category_name'       => $child->slug,
          'posts_per_page'      => $per_block,
          'ignore_sticky_posts' => true,
          'no_found_rows'       => true,
        ) );
        angbantayog_mm_block( $child->name, $q, get_category_link( $child ), $shown, $offset );
        $offset += $per_block;
      }

      /* Anything filed under Multimedia itself, or under a child that has
         not been given its own block above. Without this, posts filed at
         the parent level would silently never appear. */
      $rest = new WP_Query( array(
        'category_name'       => 'multimedia',
        'posts_per_page'      => 9,
        'post__not_in'        => $shown,
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
      ) );

      if ( $rest->have_posts() ) {
        angbantayog_mm_block(
          $children ? __( 'Iba Pa', 'ang-bantayog' ) : __( 'Lahat ng Multimedia', 'ang-bantayog' ),
          $rest,
          '',
          $shown,
          $offset
        );
      } elseif ( ! $children ) {
        ?>
        <p class="empty"><?php esc_html_e( 'Wala pang multimedia na nailathala. Gumawa ng post at itakda ang category nito sa "Multimedia".', 'ang-bantayog' ); ?></p>
        <?php
      }
      ?>

    </div>

    <aside class="portal__side" aria-label="<?php esc_attr_e( 'Karagdagang basahin', 'ang-bantayog' ); ?>">
      <div class="portal__rail">

        <div class="promo">
          <img class="promo__seal" src="<?php echo esc_url( get_template_directory_uri() . '/assets/ang-bantayog-seal.png' ); ?>" alt="" />
          <p class="promo__kicker"><?php esc_html_e( 'Tinig ng mag-aaral', 'ang-bantayog' ); ?></p>
          <blockquote class="promo__quote"><?php esc_html_e( 'Ang pahayagan ng paaralan ay hindi palamuti. Ito ay boses, tala, at tungkulin.', 'ang-bantayog' ); ?></blockquote>
          <a class="promo__cta" href="<?php echo esc_url( angbantayog_section_url( 'opinyon' ) ); ?>"><?php esc_html_e( 'Basahin ang Opinyon', 'ang-bantayog' ); ?></a>
        </div>

        <?php
        $bago    = angbantayog_feed( 4 );
        $laganap = angbantayog_popular( 4 );
        ?>
        <div class="tabs" id="sideTabs">
          <div class="tabs__bar" role="tablist">
            <button class="tabs__btn is-active" role="tab" aria-selected="true" aria-controls="tab-bago" id="btn-bago"><?php esc_html_e( 'Napapanahon', 'ang-bantayog' ); ?></button>
            <button class="tabs__btn" role="tab" aria-selected="false" aria-controls="tab-laganap" id="btn-laganap"><?php esc_html_e( 'Laganap', 'ang-bantayog' ); ?></button>
          </div>
          <div class="tabs__panel is-active" id="tab-bago" role="tabpanel" aria-labelledby="btn-bago">
            <?php if ( $bago->have_posts() ) : $i = 1; while ( $bago->have_posts() ) : $bago->the_post(); ?>
              <article class="side-item">
                <a class="side-item__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-wide', $i, '' ); ?></a>
                <div>
                  <p class="eyebrow"><?php echo esc_html( angbantayog_badge_label( get_the_ID() ) ); ?></p>
                  <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                </div>
              </article>
            <?php $i++; endwhile; wp_reset_postdata(); else : ?>
              <p class="empty"><?php esc_html_e( 'Wala pang bagong artikulo.', 'ang-bantayog' ); ?></p>
            <?php endif; ?>
          </div>
          <div class="tabs__panel" id="tab-laganap" role="tabpanel" aria-labelledby="btn-laganap" hidden>
            <?php if ( $laganap->have_posts() ) : $i = 2; while ( $laganap->have_posts() ) : $laganap->the_post(); ?>
              <article class="side-item">
                <a class="side-item__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-wide', $i, '' ); ?></a>
                <div>
                  <p class="eyebrow"><?php echo esc_html( angbantayog_badge_label( get_the_ID() ) ); ?></p>
                  <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                </div>
              </article>
            <?php $i++; endwhile; wp_reset_postdata(); else : ?>
              <p class="empty"><?php esc_html_e( 'Wala pang laganap na kuwento.', 'ang-bantayog' ); ?></p>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </aside>

  </div>

</main>

<?php get_footer(); ?>