<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="light">
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/ang-bantayog-seal.png' ); ?>" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php get_template_part( 'loader' ); ?>

<a class="skip-link" href="#content"><?php esc_html_e( 'Dumiretso sa nilalaman', 'ang-bantayog' ); ?></a>

<header class="site-header">
  <?php
  // 1. Define the slugs of the pages/categories where you want to hide the big banner
  $hide_header_slugs = array('balita', 'opinyon', 'lathalain', 'isports', 'multimedia');

  // 2. Check if we are NOT on those pages. If we are not, show the banner.
  if ( ! is_category( $hide_header_slugs ) && ! is_page( $hide_header_slugs ) && ! is_single() ) : 
  ?>
    <div class="topbar">
      <div class="container topbar__inner">
        <span class="topbar__name"><?php bloginfo( 'name' ); ?> &mdash; <?php esc_html_e( 'Opisyal na Pampaaralang Pahayagan ng Libertad National High School', 'ang-bantayog' ); ?></span>
        <time class="topbar__date" id="today"><?php echo esc_html( date_i18n( 'l, F j, Y' ) ); ?></time>
      </div>
    </div>

    <section class="masthead" aria-label="<?php esc_attr_e( 'Masthead', 'ang-bantayog' ); ?>">
      <div class="masthead__wash" aria-hidden="true"></div>
      <div class="container masthead__inner">
        <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
          <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/ang-bantayog-seal.png' ); ?>" alt="" class="brand__seal" />
          <span class="brand__text">
            <span class="brand__kicker">ANG</span>
            <strong class="brand__word">BANTAYOG</strong>
            <small class="brand__tagline"><?php esc_html_e( 'Balitang may saysay. Bantayog ng katotohanan.', 'ang-bantayog' ); ?></small>
          </span>
        </a>
      </div>
    </section>
  <?php endif; ?>

  <!-- The Navbar stays visible on ALL pages -->
  <nav class="navbar" id="navbar" aria-label="<?php esc_attr_e( 'Pangunahing menu', 'ang-bantayog' ); ?>">
    <div class="container nav__inner">
      <button class="nav__toggle" id="navToggle" aria-expanded="false" aria-controls="mainMenu">
        <span class="nav__toggle-bars" aria-hidden="true"></span>
        <span class="screen-reader-text"><?php esc_html_e( 'Buksan ang menu', 'ang-bantayog' ); ?></span>
      </button>

      <?php angbantayog_primary_menu(); ?>

      <div class="nav__search-panel" id="searchPanel" hidden><?php get_search_form(); ?></div>

      <button class="nav__search-toggle" id="searchToggle" aria-expanded="false" aria-controls="searchPanel">
        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false">
          <circle cx="10.5" cy="10.5" r="6.5" fill="none" stroke="currentColor" stroke-width="2" />
          <line x1="15.5" y1="15.5" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        </svg>
        <span class="screen-reader-text"><?php esc_html_e( 'Maghanap', 'ang-bantayog' ); ?></span>
      </button>
    </div>
  </nav>

  <?php
  /**
   * Section banner.
   */
  if ( is_category() ) :
      ?>
      <div class="section-banner" role="presentation"></div>
      <?php
  endif;
  ?>

</header>

<div id="content">