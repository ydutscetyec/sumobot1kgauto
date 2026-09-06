<?php
/**
 * Homepage: a dense, multi-column news portal.
 */
get_header();

$used = array();

/** Collect post IDs from a query so later sections can exclude them. */
function angbantayog_mark_used( $query, &$used ) {
	if ( $query && ! empty( $query->posts ) ) {
		$used = array_merge( $used, wp_list_pluck( $query->posts, 'ID' ) );
	}
}

/**
 * Pull the first oEmbed iframe out of a post's content.
 */
function angbantayog_first_embed( $post_id ) {
	$content = apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) );
	if ( preg_match( '/<iframe[^>]*>.*?<\/iframe>/is', $content, $m ) ) {
		return $m[0];
	}
	return '';
}

/* -------------------------------------------------------------------------
   QUERIES
   ------------------------------------------------------------------------- */

$daglian = angbantayog_feed( 5 );

/** PANGUNAHING ISTORYA (Manual Selection) */
$lead = new WP_Query( array(
	'posts_per_page' => 3,
	'meta_key'       => '_angbantayog_pangunahing',
	'meta_value'     => '1',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
) );
angbantayog_mark_used( $lead, $used );

/** PINILI NG EDITOR (Manual Selection) */
$picks = new WP_Query( array(
	'posts_per_page' => 2,
	'meta_key'       => '_angbantayog_pinili',
	'meta_value'     => '1',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
) );
angbantayog_mark_used( $picks, $used );

/** KUWENTONG SIKAT (Manual Selection) */
$sikat = new WP_Query( array(
	'posts_per_page' => 5,
	'meta_key'       => '_angbantayog_sikat',
	'meta_value'     => '1',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
) );

$balita = angbantayog_section_query( 'balita', 5 );
angbantayog_mark_used( $balita, $used );

$lente = angbantayog_section_query( 'multimedia', 1 );
angbantayog_mark_used( $lente, $used );

/**
 * OPINYON — includes parent "Opinyon" plus child categories
 * "Editoryal" and "Kolumn" so all opinion-style posts appear here.
 */
$opinyon_parent = get_category_by_slug( 'opinyon' );
$opinyon_cat_ids = array();
if ( $opinyon_parent ) {
    $opinyon_cat_ids[] = $opinyon_parent->term_id;
    $opinyon_children = get_categories( array(
        'parent'     => $opinyon_parent->term_id,
        'hide_empty' => false,
    ) );
    foreach ( $opinyon_children as $child ) {
        $opinyon_cat_ids[] = $child->term_id;
    }
}
$opinyon = new WP_Query( array(
    'posts_per_page' => 4,
    'category__in'   => $opinyon_cat_ids,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
) );
angbantayog_mark_used( $opinyon, $used );

$lathalain = angbantayog_section_query( 'lathalain', 5 );
angbantayog_mark_used( $lathalain, $used );

$isports = angbantayog_section_query( 'isports', 3 );
angbantayog_mark_used( $isports, $used );

/** NAPAPANAHON (Manual Selection) */
$bago = new WP_Query( array(
	'posts_per_page' => 4,
	'meta_key'       => '_angbantayog_napapanahon',
	'meta_value'     => '1',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
) );

/** LAGANAP (Manual Selection) */
$laganap = new WP_Query( array(
	'posts_per_page' => 4,
	'meta_key'       => '_angbantayog_laganap',
	'meta_value'     => '1',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
) );

$latest = angbantayog_feed( 5 );

/* Anything the page has not shown yet. */
$nakaligtaan = angbantayog_feed( 5, $used );
if ( ! $nakaligtaan->have_posts() ) {
	$nakaligtaan = angbantayog_feed( 5 );
}
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
                <?php foreach ( angbantayog_badge_labels( get_the_ID() ) as $label ) : ?>
                  <span class="tag tag--solid"><?php echo $label; ?></span>
                <?php endforeach; ?>
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
              <p class="byline"><?php esc_html_e( 'I-check ang "Pangunahing Istorya" sa post editor para lumabas dito.', 'ang-bantayog' ); ?></p>
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
            <p class="tag-row">
              <?php foreach ( angbantayog_badge_labels( get_the_ID() ) as $label ) : ?>
                <span class="tag tag--solid"><?php echo $label; ?></span>
              <?php endforeach; ?>
              <span class="tag"><?php esc_html_e( 'Pinili ng Editor', 'ang-bantayog' ); ?></span>
            </p>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p class="byline"><?php echo esc_html( get_the_author() ); ?> &middot; <?php echo esc_html( get_the_date() ); ?></p>
          </div>
        </article>
      <?php $i++; endwhile; wp_reset_postdata(); else : ?>
        <article class="pick-card pick-card--empty">
          <div class="pick-card__body">
            <h3><?php esc_html_e( 'Wala pang pinili ang editor', 'ang-bantayog' ); ?></h3>
            <p class="byline"><?php esc_html_e( 'I-check ang "Pinili ng Editor" sa post editor para lumabas dito.', 'ang-bantayog' ); ?></p>
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
              <p class="eyebrow"><?php echo esc_html( implode( ', ', angbantayog_badge_labels( get_the_ID() ) ) ); ?></p>
              <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            </div>
          </li>
        <?php $i++; endwhile; wp_reset_postdata(); else : ?>
          <li class="rank rank--empty"><?php esc_html_e( 'I-check ang "Kuwentong Sikat" sa post editor para lumabas dito.', 'ang-bantayog' ); ?></li>
        <?php endif; ?>
      </ol>
    </div>

  </section>

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

  <div class="container portal">

    <div class="portal__main">

      <section class="band band--first" id="multimedia">
        <div class="band-head">
          <h2><?php esc_html_e( 'Multimedia', 'ang-bantayog' ); ?></h2>
          <a class="band-more" href="<?php echo esc_url( angbantayog_section_url( 'multimedia' ) ); ?>"><?php esc_html_e( 'Lahat', 'ang-bantayog' ); ?></a>
        </div>
        <?php if ( $lente->have_posts() ) : while ( $lente->have_posts() ) : $lente->the_post(); ?>
          <?php $embed = angbantayog_first_embed( get_the_ID() ); ?>
          <article class="lente-card">
            <?php if ( $embed ) : ?>
              <?php $is_short = false !== strpos( get_post_field( 'post_content', get_the_ID() ), '/shorts/' ); ?>
              <div class="lente-card__embed<?php echo $is_short ? ' lente-card__embed--portrait' : ''; ?>">
                <?php echo $embed; ?>
              </div>
            <?php else : ?>
              <a class="lente-card__media" href="<?php the_permalink(); ?>">
                <?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-featured', 4, '' ); ?>
                <span class="play" aria-hidden="true"></span>
              </a>
            <?php endif; ?>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p class="byline"><?php echo esc_html( get_the_author() ); ?> &middot; <?php echo esc_html( get_the_date() ); ?></p>
          </article>
        <?php endwhile; wp_reset_postdata(); else : ?>
          <article class="lente-card">
            <div class="lente-card__media"><img src="<?php echo esc_url( angbantayog_fallback_thumb( 4 ) ); ?>" alt="" /><span class="play" aria-hidden="true"></span></div>
            <h3><?php esc_html_e( 'Wala pang multimedia na nailathala', 'ang-bantayog' ); ?></h3>
            <p class="byline"><?php esc_html_e( 'Itakda ang category ng isang post sa "Multimedia" para lumabas ito rito.', 'ang-bantayog' ); ?></p>
          </article>
        <?php endif; ?>
      </section>

      <section class="kolum-grid">

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
                  <!-- DYNAMIC TAG: shows Editoryal, Kolumn, or Opinyon automatically -->
                  <p class="tag-row">
                    <?php foreach ( angbantayog_badge_labels( get_the_ID() ) as $label ) : ?>
                      <span class="tag tag--solid"><?php echo $label; ?></span>
                    <?php endforeach; ?>
                  </p>
                  <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                  <p class="byline"><?php echo esc_html( get_the_author() ); ?> &middot; <?php echo esc_html( get_the_date() ); ?></p>
                </div>
              </article>
            <?php else : ?>
              <article class="kolum-card">
                <a class="kolum-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                  <?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-wide', 0, '' ); ?>
                  <blockquote class="kolum-card__pull"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></blockquote>
                </a>
                <div class="kolum-card__body">
                  <!-- DYNAMIC EYEBROW: shows the actual category (Editoryal/Kolumn/Opinyon) -->
                  <p class="eyebrow"><?php echo esc_html( implode( ', ', angbantayog_badge_labels( get_the_ID() ) ) ); ?></p>
                  <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                  <p class="byline"><?php echo esc_html( get_the_author() ); ?> &middot; <?php echo esc_html( get_the_date() ); ?></p>
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
                <p class="eyebrow"><?php echo esc_html( implode( ', ', angbantayog_badge_labels( get_the_ID() ) ) ); ?></p>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p class="byline"><?php echo esc_html( get_the_author() ); ?> &middot; <?php echo esc_html( get_the_date() ); ?></p>
              </div>
            </article>
          <?php $i++; endwhile; wp_reset_postdata(); else : ?>
            <p class="empty"><?php esc_html_e( 'Wala pang lathalain na nailathala.', 'ang-bantayog' ); ?></p>
          <?php endif; ?>
        </div>

      </section>
      
      <section class="weather-banner" aria-label="<?php esc_attr_e( 'Panahon ngayon', 'ang-bantayog' ); ?>">
        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/weather.gif' ); ?>" 
             alt="<?php esc_attr_e( 'Weather forecast for Koronadal, South Cotabato', 'ang-bantayog' ); ?>" 
             loading="lazy" />
      </section>

      <section class="band" id="isports">
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

    </div>

    <aside class="portal__side" aria-label="<?php esc_attr_e( 'Karagdagang basahin', 'ang-bantayog' ); ?>">
      <div class="portal__rail">

        <div class="promo">
          <img class="promo__seal" src="<?php echo esc_url( get_template_directory_uri() . '/assets/ang-bantayog-seal.png' ); ?>" alt="" />
          <p class="promo__kicker"><?php esc_html_e( 'Tinig ng mag-aaral', 'ang-bantayog' ); ?></p>
          <blockquote class="promo__quote"><?php esc_html_e( 'Ang pahayagan ng paaralan ay hindi palamuti. Ito ay boses, tala, at tungkulin.', 'ang-bantayog' ); ?></blockquote>
          <a class="promo__dspc" href="<?php echo esc_url( angbantayog_section_url( 'opinyon' ) ); ?>">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dspc-na.gif' ); ?>" 
                alt="<?php esc_attr_e( 'DSPC Na!', 'ang-bantayog' ); ?>" 
                loading="lazy" />
          </a>
        </div>

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
                  <p class="eyebrow"><?php echo esc_html( implode( ', ', angbantayog_badge_labels( get_the_ID() ) ) ); ?></p>
                  <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                </div>
              </article>
            <?php $i++; endwhile; wp_reset_postdata(); else : ?>
              <p class="empty"><?php esc_html_e( 'I-check ang "Napapanahon" sa post editor para lumabas dito.', 'ang-bantayog' ); ?></p>
            <?php endif; ?>
          </div>
          <div class="tabs__panel" id="tab-laganap" role="tabpanel" aria-labelledby="btn-laganap" hidden>
            <?php if ( $laganap->have_posts() ) : $i = 2; while ( $laganap->have_posts() ) : $laganap->the_post(); ?>
              <article class="side-item">
                <a class="side-item__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-wide', $i, '' ); ?></a>
                <div>
                  <p class="eyebrow"><?php echo esc_html( implode( ', ', angbantayog_badge_labels( get_the_ID() ) ) ); ?></p>
                  <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                </div>
              </article>
            <?php $i++; endwhile; wp_reset_postdata(); else : ?>
              <p class="empty"><?php esc_html_e( 'I-check ang "Laganap" sa post editor para lumabas dito.', 'ang-bantayog' ); ?></p>
            <?php endif; ?>
          </div>
        </div>

        <div class="rail-block hanapin-salita">
          <h3 class="rail-block__title"><?php esc_html_e( 'Hanapin ang Salita', 'ang-bantayog' ); ?></h3>
          <a href="<?php echo esc_url( get_template_directory_uri() . '/assets/hanapin-salita.gif' ); ?>" 
             class="hanapin-salita__image" 
             target="_blank" 
             rel="noopener">
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/hanapin-salita.gif' ); ?>" 
                 alt="<?php esc_attr_e( 'Hanapin ang Salita - Word Search Puzzle', 'ang-bantayog' ); ?>" 
                 loading="lazy" />
          </a>
        </div>

                <!-- ========================================== -->
        <!--  CURRENCY EXCHANGE START 👇 -->
        <!-- ========================================== -->
        <div class="rail-block currency-exchange">
          <h3 class="rail-block__title" style="margin-bottom: 15px;">💱 Currency Exchange</h3>
          
                    <style>
            /* Base styles (Forced to stay visible) */
            .currency-exchange {
              width: 100%;
              padding: 20px;
              background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%) !important;
              border-radius: 12px;
              box-shadow: 0 4px 15px rgba(0,0,0,0.05);
              font-family: inherit;
              color: #333 !important; /* Forces text to stay dark */
              box-sizing: border-box;
              margin-top: 20px;
            }
            .currency-exchange * {
              box-sizing: border-box;
            }
            .exchange-grid {
              display: flex;
              flex-direction: column;
              gap: 12px;
            }
            .input-group {
              display: flex;
              flex-direction: column;
            }
            .input-group label {
              font-size: 0.8rem;
              font-weight: 600;
              margin-bottom: 4px;
              color: #555 !important;
            }
            .input-group input, 
            .input-group select {
              padding: 10px;
              border: 1px solid #ddd !important;
              border-radius: 6px;
              font-size: 0.95rem;
              background: white !important;
              color: #333 !important;
              width: 100%;
            }
            .input-group input:focus, 
            .input-group select:focus {
              outline: none;
              border-color: #003366 !important;
            }
            .convert-btn {
              padding: 12px;
              background: #003366 !important;
              color: white !important;
              border: none;
              border-radius: 6px;
              font-size: 1rem;
              font-weight: bold;
              cursor: pointer;
              transition: background 0.3s;
              width: 100%;
            }
            .convert-btn:hover { background: #004080 !important; }
            .result-display {
              text-align: center;
              margin-top: 15px;
              padding: 12px;
              background: white !important;
              border-radius: 8px;
              border: 1px dashed #003366 !important;
              min-height: 50px;
              display: flex;
              flex-direction: column;
              justify-content: center;
              color: #333 !important;
            }
            .result-amount { font-size: 1.4rem; font-weight: bold; color: #003366 !important; }
            .result-rate { font-size: 0.75rem; color: #666 !important; margin-top: 4px; }
            .update-info { text-align: center; font-size: 0.7rem; color: #888 !important; margin-top: 10px; margin-bottom: 0;}
            .loading { color: #888 !important; font-style: italic; font-size: 0.9rem;}

            /* ========================================== */
            /* DARK MODE STYLES (Matches your theme)      */
            /* ========================================== */
            body.dark-mode .currency-exchange,
            body.theme-dark .currency-exchange,
            html[data-theme="dark"] .currency-exchange,
            .dark-mode .currency-exchange {
              background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%) !important;
              color: #f0f0f0 !important;
              box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            }
            body.dark-mode .currency-exchange .input-group label,
            body.theme-dark .currency-exchange .input-group label,
            html[data-theme="dark"] .currency-exchange .input-group label {
              color: #bdc3c7 !important;
            }
            body.dark-mode .currency-exchange .input-group input,
            body.dark-mode .currency-exchange .input-group select,
            body.theme-dark .currency-exchange .input-group input,
            body.theme-dark .currency-exchange .input-group select,
            html[data-theme="dark"] .currency-exchange .input-group input,
            html[data-theme="dark"] .currency-exchange .input-group select {
              background: #34495e !important;
              border: 1px solid #4a6278 !important;
              color: #f0f0f0 !important;
            }
            body.dark-mode .currency-exchange .result-display,
            body.theme-dark .currency-exchange .result-display,
            html[data-theme="dark"] .currency-exchange .result-display {
              background: #34495e !important;
              border: 1px dashed #3498db !important;
              color: #f0f0f0 !important;
            }
            body.dark-mode .currency-exchange .result-amount,
            body.theme-dark .currency-exchange .result-amount,
            html[data-theme="dark"] .currency-exchange .result-amount {
              color: #3498db !important;
            }
            body.dark-mode .currency-exchange .convert-btn,
            body.theme-dark .currency-exchange .convert-btn,
            html[data-theme="dark"] .currency-exchange .convert-btn {
              background: #2980b9 !important;
            }
            body.dark-mode .currency-exchange .update-info,
            body.theme-dark .currency-exchange .update-info,
            html[data-theme="dark"] .currency-exchange .update-info {
              color: #95a5a6 !important;
            }
          </style>

          <div class="exchange-grid">
            <div class="input-group">
              <label for="amount">Amount</label>
              <input type="number" id="amount" value="1" min="0" step="any">
            </div>
            
            <div class="input-group">
              <label for="fromCurrency">From</label>
              <select id="fromCurrency">
                <option value="PHP">PHP - Philippine Peso</option>
                <option value="USD">USD - US Dollar</option>
                <option value="EUR">EUR - Euro</option>
                <option value="JPY">JPY - Japanese Yen</option>
                <option value="SGD">SGD - Singapore Dollar</option>
              </select>
            </div>
            
            <div class="input-group">
              <label for="toCurrency">To</label>
              <select id="toCurrency">
                <option value="USD">USD - US Dollar</option>
                <option value="PHP" selected>PHP - Philippine Peso</option>
                <option value="EUR">EUR - Euro</option>
                <option value="JPY">JPY - Japanese Yen</option>
                <option value="SGD">SGD - Singapore Dollar</option>
              </select>
            </div>

            <button id="convertBtn" class="convert-btn">Convert</button>
          </div>

          <div id="resultDisplay" class="result-display">
            <span class="loading">Fetching live rates...</span>
          </div>

          <p id="updateInfo" class="update-info">Rates update in real-time</p>

          <script>
            document.addEventListener('DOMContentLoaded', () => {
              const amountInput = document.getElementById('amount');
              const fromSelect = document.getElementById('fromCurrency');
              const toSelect = document.getElementById('toCurrency');
              const convertBtn = document.getElementById('convertBtn');
              const resultDisplay = document.getElementById('resultDisplay');
              const updateInfo = document.getElementById('updateInfo');

              let exchangeRates = {};
              let baseCurrency = 'USD';

              async function fetchRates() {
                resultDisplay.innerHTML = '<span class="loading">Fetching live rates...</span>';
                try {
                  const response = await fetch(`https://api.exchangerate-api.com/v4/latest/${baseCurrency}`);
                  const data = await response.json();
                  exchangeRates = data.rates;
                  
                  const now = new Date();
                  updateInfo.textContent = `Updated: ${now.toLocaleTimeString()}`;
                  convertCurrency();
                } catch (error) {
                  resultDisplay.innerHTML = '<span style="color:red; font-size:0.8rem;">Error fetching rates.</span>';
                }
              }

              function convertCurrency() {
                const amount = parseFloat(amountInput.value);
                const from = fromSelect.value;
                const to = toSelect.value;

                if (isNaN(amount) || amount <= 0) {
                  resultDisplay.innerHTML = '<span style="font-size:0.9rem;">Enter amount</span>';
                  return;
                }

                if (!exchangeRates[from] || !exchangeRates[to]) return;

                const amountInBase = amount / exchangeRates[from];
                const convertedAmount = amountInBase * exchangeRates[to];
                const rate = (1 / exchangeRates[from]) * exchangeRates[to];

                resultDisplay.innerHTML = `
                  <div class="result-amount">${convertedAmount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ${to}</div>
                  <div class="result-rate">1 ${from} = ${rate.toFixed(4)} ${to}</div>
                `;
              }

              convertBtn.addEventListener('click', convertCurrency);
              amountInput.addEventListener('input', convertCurrency);
              fromSelect.addEventListener('change', convertCurrency);
              toSelect.addEventListener('change', convertCurrency);

              fetchRates();
              setInterval(fetchRates, 600000); 
            });
          </script>
        </div>
        <!-- ========================================== -->
        <!-- 👆 CURRENCY EXCHANGE END 👆 -->
        <!-- ========================================== -->

      </div>
    </aside>

  </div>

  <section class="container band" id="nakaligtaan">
    <div class="band-head">
      <h2><?php esc_html_e( 'Mga Nakaligtaan', 'ang-bantayog' ); ?></h2>
    </div>
    <div class="card-row">
      <?php if ( $nakaligtaan->have_posts() ) : $i = 0; while ( $nakaligtaan->have_posts() ) : $nakaligtaan->the_post(); ?>
        <article class="card">
          <a class="card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
            <?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-wide', $i + 2, '' ); ?>
            <span class="tag tag--solid tag--pinned"><?php echo esc_html( implode( ', ', angbantayog_badge_labels( get_the_ID() ) ) ); ?></span>
          </a>
          <h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <p class="byline"><?php echo esc_html( get_the_author() ); ?> &middot; <?php echo esc_html( get_the_date() ); ?></p>
        </article>
      <?php $i++; endwhile; wp_reset_postdata(); else : ?>
        <p class="empty"><?php esc_html_e( 'Wala pang ibang artikulo.', 'ang-bantayog' ); ?></p>
      <?php endif; ?>
    </div>
  </section>

</main>

<?php get_footer(); ?>