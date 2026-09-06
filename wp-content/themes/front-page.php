<?php
/**
 * Homepage: a dense, multi-column news portal.
 *
 * Layout map
 *   Daglian ....... scrolling headline strip
 *   Lead grid ..... Pangunahing Istorya / Mga Pinili ng Editor / Kuwentong Sikat
 *   Balita ........ five-across card row
 *   Lente ......... featured multimedia post + section promo
 *   Kolum ......... Opinyon / Lathalain columns + Napapanahon-Laganap sidebar
 *   Isports ....... wide feature slider
 *
 * Editor's Picks reads WordPress sticky posts; Kuwentong Sikat and Laganap
 * rank by comment count. Every section degrades to an empty-state card so a
 * thin site still looks finished.
 */
get_header();

$used = array();

$daglian  = angbantayog_feed( 5 );
$lead     = angbantayog_feed( 3 );
$lead_ids = wp_list_pluck( $lead->posts, 'ID' );
$used     = array_merge( $used, $lead_ids );

$picks = new WP_Query( array(
	'posts_per_page'      => 2,
	'post__in'            => get_option( 'sticky_posts' ) ? get_option( 'sticky_posts' ) : array(),
	'post__not_in'        => $used,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
) );
if ( ! $picks->have_posts() ) {
	$picks = angbantayog_feed( 2, $used );
}
$used = array_merge( $used, wp_list_pluck( $picks->posts, 'ID' ) );

$sikat = angbantayog_popular( 5 );
?>

<main class="home">

  <section class="daglian" aria-label="<?php esc_attr_e( 'Pinakabagong balita', 'ang-bantayog' ); ?>">
    <div class="container daglian__inner">
      <span class="daglian__tag"><?php esc_html_e( 'DAGLIAN', 'ang-bantayog' ); ?></span>
      <div class="daglian__viewport">
        <div class="daglian__track" id="daglianTrack">
          <?php if ( $daglian->have_posts() ) : while ( $daglian->have_posts() ) : $daglian->the_post(); ?>
            <a class="daglian__item" href="<?php the_permalink(); ?>">
              <span class="daglian__dot" aria-hidden="true"></span>
              <?php the_title(); ?>
            </a>
          <?php endwhile; wp_reset_postdata(); else : ?>
            <span class="daglian__item"><?php esc_html_e( 'Maligayang pagdating sa digital home ng ANG BANTAYOG.', 'ang-bantayog' ); ?></span>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="container lead-grid" aria-label="<?php esc_attr_e( 'Mga pangunahing kuwento', 'ang-bantayog' ); ?>">

    <div class="lead-main">
      <div class="band-head">
        <h2><?php esc_html_e( 'Pangunahing Istorya', 'ang-bantayog' ); ?></h2>
        <div class="slider-nav">
          <button class="slider-btn" data-slider="lead" data-dir="prev" aria-label="<?php esc_attr_e( 'Naunang kuwento', 'ang-bantayog' ); ?>">&#8249;</button>
          <button class="slider-btn" data-slider="lead" data-dir="next" aria-label="<?php esc_attr_e( 'Susunod na kuwento', 'ang-bantayog' ); ?>">&#8250;</button>
        </div>
      </div>
      <div class="slider" data-slider-track="lead">
        <?php if ( $lead->have_posts() ) : $i = 0; while ( $lead->have_posts() ) : $lead->the_post(); ?>
          <article class="hero-card slide<?php echo 0 === $i ? ' is-active' : ''; ?>">
            <a class="hero-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
              <?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-featured', $i, '' ); ?>
            </a>
            <div class="hero-card__body">
              <p class="tag-row">
                <span class="tag tag--solid"><?php echo esc_html( angbantayog_badge_label( get_the_ID() ) ); ?></span>
                <span class="tag"><?php esc_html_e( 'Pangunahing Istorya', 'ang-bantayog' ); ?></span>
              </p>
              <h3 class="hero-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p class="byline"><?php echo esc_html( get_the_author() ); ?> &middot; <?php echo esc_html( get_the_date() ); ?> &middot; <?php echo esc_html( angbantayog_reading_time( get_the_ID() ) ); ?></p>
            </div>
          </article>
        <?php $i++; endwhile; wp_reset_postdata(); else : ?>
          <article class="hero-card slide is-active">
            <div class="hero-card__media"><img src="<?php echo esc_url( angbantayog_fallback_thumb( 0 ) ); ?>" alt="" /></div>
            <div class="hero-card__body">
              <p class="tag-row"><span class="tag tag--solid"><?php esc_html_e( 'Balita', 'ang-bantayog' ); ?></span></p>
              <h3 class="hero-card__title"><?php esc_html_e( 'Wala pang nailathalang kuwento', 'ang-bantayog' ); ?></h3>
              <p class="byline"><?php esc_html_e( 'I-publish ang unang artikulo para mapunan ang espasyong ito.', 'ang-bantayog' ); ?></p>
            </div>
          </article>
        <?php endif; ?>
      </div>
    </div>

    <div class="lead-picks">
      <div class="band-head"><h2><?php esc_html_e( 'Mga Pinili ng Editor', 'ang-bantayog' ); ?></h2></div>
      <?php if ( $picks->have_posts() ) : $i = 1; while ( $picks->have_posts() ) : $picks->the_post(); ?>
        <article class="pick-card">
          <a class="pick-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
            <?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-wide', $i, '' ); ?>
          </a>
          <div class="pick-card__body">
            <p class="tag-row"><span class="tag tag--solid"><?php echo esc_html( angbantayog_badge_label( get_the_ID() ) ); ?></span><span class="tag"><?php esc_html_e( 'Pinili ng Editor', 'ang-bantayog' ); ?></span></p>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p class="byline"><?php echo esc_html( get_the_author() ); ?> &middot; <?php echo esc_html( get_the_date() ); ?></p>
          </div>
        </article>
      <?php $i++; endwhile; wp_reset_postdata(); else : ?>
        <article class="pick-card pick-card--empty">
          <div class="pick-card__body">
            <h3><?php esc_html_e( 'Wala pang pinili ang editor', 'ang-bantayog' ); ?></h3>
            <p class="byline"><?php esc_html_e( 'I-sticky ang isang post para itampok ito rito.', 'ang-bantayog' ); ?></p>
          </div>
        </article>
      <?php endif; ?>
    </div>

    <div class="lead-sikat">
      <div class="band-head"><h2><?php esc_html_e( 'Kuwentong Sikat', 'ang-bantayog' ); ?></h2></div>
      <ol class="rank-list">
        <?php if ( $sikat->have_posts() ) : $i = 1; while ( $sikat->have_posts() ) : $sikat->the_post(); ?>
          <li class="rank">
            <span class="rank__num" aria-hidden="true"><?php echo esc_html( $i ); ?></span>
            <a class="rank__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
              <?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-wide', $i, '' ); ?>
            </a>
            <div class="rank__body">
              <p class="eyebrow"><?php echo esc_html( angbantayog_badge_label( get_the_ID() ) ); ?></p>
              <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            </div>
          </li>
        <?php $i++; endwhile; wp_reset_postdata(); else : ?>
          <li class="rank rank--empty"><?php esc_html_e( 'Lalabas dito ang pinakabinabasang kuwento.', 'ang-bantayog' ); ?></li>
        <?php endif; ?>
      </ol>
    </div>

  </section>

  <?php $balita = angbantayog_section_query( 'balita', 5 ); ?>
  <section class="container band" id="balita">
    <div class="band-head">
      <h2><?php esc_html_e( 'Balita', 'ang-bantayog' ); ?></h2>
      <a class="band-more" href="<?php echo esc_url( angbantayog_section_url( 'balita' ) ); ?>"><?php esc_html_e( 'Tingnan lahat', 'ang-bantayog' ); ?></a>
    </div>
    <div class="card-row">
      <?php if ( $balita->have_posts() ) : $i = 0; while ( $balita->have_posts() ) : $balita->the_post(); ?>
        <article class="card">
          <a class="card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
            <?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-wide', $i, '' ); ?>
            <span class="tag tag--solid tag--pinned"><?php esc_html_e( 'Balita', 'ang-bantayog' ); ?></span>
          </a>
          <h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <p class="byline"><?php echo esc_html( get_the_author() ); ?> &middot; <?php echo esc_html( get_the_date() ); ?></p>
        </article>
      <?php $i++; endwhile; wp_reset_postdata(); else : ?>
        <p class="empty"><?php esc_html_e( 'Wala pang artikulo sa Balita. Gumawa ng post at itakda ang category nito sa "Balita".', 'ang-bantayog' ); ?></p>
      <?php endif; ?>
    </div>
  </section>

  <?php $lente = angbantayog_section_query( 'multimedia', 1 ); ?>
  <section class="container lente-grid" id="multimedia">
    <div class="lente-main">
      <div class="band-head"><h2><?php esc_html_e( 'Lente Bantayog', 'ang-bantayog' ); ?></h2></div>
      <?php if ( $lente->have_posts() ) : while ( $lente->have_posts() ) : $lente->the_post(); ?>
        <article class="lente-card">
          <a class="lente-card__media" href="<?php the_permalink(); ?>">
            <?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-featured', 4, '' ); ?>
            <span class="play" aria-hidden="true"></span>
          </a>
          <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        </article>
      <?php endwhile; wp_reset_postdata(); else : ?>
        <article class="lente-card">
          <div class="lente-card__media"><img src="<?php echo esc_url( angbantayog_fallback_thumb( 4 ) ); ?>" alt="" /><span class="play" aria-hidden="true"></span></div>
          <h3><?php esc_html_e( 'Wala pang multimedia na nailathala', 'ang-bantayog' ); ?></h3>
          <p class="byline"><?php esc_html_e( 'Itakda ang category ng isang post sa "Multimedia" para lumabas ito rito.', 'ang-bantayog' ); ?></p>
        </article>
      <?php endif; ?>
    </div>
    <aside class="promo">
      <img class="promo__seal" src="<?php echo esc_url( get_template_directory_uri() . '/assets/ang-bantayog-seal.png' ); ?>" alt="" />
      <p class="promo__kicker"><?php esc_html_e( 'Tinig ng mag-aaral', 'ang-bantayog' ); ?></p>
      <blockquote class="promo__quote"><?php esc_html_e( 'Ang pahayagan ng paaralan ay hindi palamuti. Ito ay boses, tala, at tungkulin.', 'ang-bantayog' ); ?></blockquote>
      <a class="promo__cta" href="<?php echo esc_url( angbantayog_section_url( 'opinyon' ) ); ?>"><?php esc_html_e( 'Basahin ang Opinyon', 'ang-bantayog' ); ?></a>
    </aside>
  </section>

  <?php
  $opinyon   = angbantayog_section_query( 'opinyon', 4 );
  $lathalain = angbantayog_section_query( 'lathalain', 5 );
  $bago      = angbantayog_feed( 4 );
  $laganap   = angbantayog_popular( 4 );
  ?>
  <section class="container kolum-grid">

    <div class="kolum" id="opinyon">
      <div class="band-head">
        <h2><?php esc_html_e( 'Opinyon', 'ang-bantayog' ); ?></h2>
        <a class="band-more" href="<?php echo esc_url( angbantayog_section_url( 'opinyon' ) ); ?>"><?php esc_html_e( 'Lahat', 'ang-bantayog' ); ?></a>
      </div>
      <?php if ( $opinyon->have_posts() ) : $i = 0; while ( $opinyon->have_posts() ) : $opinyon->the_post(); ?>
        <?php if ( 0 === $i ) : ?>
          <article class="opinion-lead">
            <a class="opinion-lead__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
              <?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-wide', 0, '' ); ?>
            </a>
            <div class="opinion-lead__body">
              <p class="tag-row"><span class="tag tag--solid"><?php esc_html_e( 'Editoryal', 'ang-bantayog' ); ?></span></p>
              <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p class="byline"><?php echo esc_html( get_the_author() ); ?> &middot; <?php echo esc_html( get_the_date() ); ?></p>
            </div>
          </article>
        <?php else : ?>
          <article class="kolum-card">
            <blockquote class="kolum-card__pull"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></blockquote>
            <div class="kolum-card__body">
              <p class="eyebrow"><?php esc_html_e( 'Kolum', 'ang-bantayog' ); ?> &middot; <?php echo esc_html( angbantayog_badge_label( get_the_ID() ) ); ?></p>
              <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p class="byline"><?php echo esc_html( get_the_author() ); ?></p>
            </div>
          </article>
        <?php endif; ?>
      <?php $i++; endwhile; wp_reset_postdata(); else : ?>
        <p class="empty"><?php esc_html_e( 'Wala pang opinyon na nailathala.', 'ang-bantayog' ); ?></p>
      <?php endif; ?>
    </div>

    <div class="kolum" id="lathalain">
      <div class="band-head">
        <h2><?php esc_html_e( 'Lathalain', 'ang-bantayog' ); ?></h2>
        <a class="band-more" href="<?php echo esc_url( angbantayog_section_url( 'lathalain' ) ); ?>"><?php esc_html_e( 'Lahat', 'ang-bantayog' ); ?></a>
      </div>
      <?php if ( $lathalain->have_posts() ) : $i = 1; while ( $lathalain->have_posts() ) : $lathalain->the_post(); ?>
        <article class="list-item">
          <a class="list-item__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
            <?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-wide', $i, '' ); ?>
          </a>
          <div class="list-item__body">
            <p class="eyebrow"><?php echo esc_html( angbantayog_badge_label( get_the_ID() ) ); ?></p>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p class="byline"><?php echo esc_html( get_the_author() ); ?> &middot; <?php echo esc_html( get_the_date() ); ?></p>
          </div>
        </article>
      <?php $i++; endwhile; wp_reset_postdata(); else : ?>
        <p class="empty"><?php esc_html_e( 'Wala pang lathalain na nailathala.', 'ang-bantayog' ); ?></p>
      <?php endif; ?>
    </div>

    <aside class="kolum-side">
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
    </aside>

  </section>

  <?php $isports = angbantayog_section_query( 'isports', 3 ); ?>
  <section class="container band" id="isports">
    <div class="band-head">
      <h2><?php esc_html_e( 'Isports', 'ang-bantayog' ); ?></h2>
      <div class="slider-nav">
        <button class="slider-btn" data-slider="isports" data-dir="prev" aria-label="<?php esc_attr_e( 'Naunang kuwento', 'ang-bantayog' ); ?>">&#8249;</button>
        <button class="slider-btn" data-slider="isports" data-dir="next" aria-label="<?php esc_attr_e( 'Susunod na kuwento', 'ang-bantayog' ); ?>">&#8250;</button>
      </div>
    </div>
    <div class="slider" data-slider-track="isports">
      <?php if ( $isports->have_posts() ) : $i = 0; while ( $isports->have_posts() ) : $isports->the_post(); ?>
        <article class="hero-card hero-card--wide slide<?php echo 0 === $i ? ' is-active' : ''; ?>">
          <a class="hero-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
            <?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-featured', $i + 3, '' ); ?>
          </a>
          <div class="hero-card__body">
            <p class="tag-row"><span class="tag tag--solid"><?php esc_html_e( 'Isports', 'ang-bantayog' ); ?></span></p>
            <h3 class="hero-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p class="byline"><?php echo esc_html( get_the_author() ); ?> &middot; <?php echo esc_html( get_the_date() ); ?></p>
          </div>
        </article>
      <?php $i++; endwhile; wp_reset_postdata(); else : ?>
        <article class="hero-card hero-card--wide slide is-active">
          <div class="hero-card__media"><img src="<?php echo esc_url( angbantayog_fallback_thumb( 3 ) ); ?>" alt="" /></div>
          <div class="hero-card__body">
            <p class="tag-row"><span class="tag tag--solid"><?php esc_html_e( 'Isports', 'ang-bantayog' ); ?></span></p>
            <h3 class="hero-card__title"><?php esc_html_e( 'Wala pang balitang isports', 'ang-bantayog' ); ?></h3>
          </div>
        </article>
      <?php endif; ?>
    </div>
  </section>

</main>

<?php get_footer(); ?>
