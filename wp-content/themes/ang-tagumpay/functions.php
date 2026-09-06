<?php
/**
 * Ang Tagumpay theme functions
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ANGBANTAYOG_VERSIOn', '1.0' );

/**
 * Theme setup
 */
function angtagumpay_setup() {
	load_theme_textdomain( 'ang-bantayog', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'customize-selective-refresh-widgets' );

	set_post_thumbnail_size( 800, 500, true );
	add_image_size( 'angtagumpay-featured', 1100, 620, true );
	add_image_size( 'angtagumpay-wide', 700, 500, true );

	register_nav_menus( array(
		'primary' => __( 'Pangunahing Menu (Primary)', 'ang-tagumpay' ),
	) );
}
add_action( 'after_setup_theme', 'angtagumpay_setup' );

/**
 * Enqueue styles and scripts
 */
function angtagumpay_scripts() {
	wp_enqueue_style( 'ang-tagumpay-style', get_stylesheet_uri(), array(), ANGTAGUMPAY_VERSION );
	wp_enqueue_script( 'ang-tagumpay-script', get_template_directory_uri() . '/script.js', array(), ANGTAGUMPAY_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'angtagumpay_scripts' );

/**
 * Register widget area (used in footer, optional)
 */
function angtagumpay_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer', 'ang-tagumpay' ),
		'id'            => 'footer-1',
		'description'   => __( 'Widgets dito ay lalabas sa footer.', 'ang-tagumpay' ),
		'before_widget' => '<div class="footer-widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'angtagumpay_widgets_init' );

/**
 * Fallback thumbnails so the site still looks complete before real photos are uploaded.
 */
function angtagumpay_fallback_thumb( $index = 0 ) {
	$fallbacks = array(
		'article-1.svg', 'article-2.svg', 'article-3.svg',
		'article-4.svg', 'article-5.svg', 'article-6.svg',
	);
	$file = $fallbacks[ $index % count( $fallbacks ) ];
	return get_template_directory_uri() . '/assets/' . $file;
}

/**
 * Print a post's thumbnail (real featured image if set, otherwise a themed placeholder).
 */
function angtagumpay_post_thumb( $post_id, $size = 'angtagumpay-featured', $fallback_index = 0, $alt = '' ) {
	if ( has_post_thumbnail( $post_id ) ) {
		echo get_the_post_thumbnail( $post_id, $size, array( 'alt' => esc_attr( $alt ) ) );
	} else {
		printf(
			'<img src="%s" alt="%s" loading="lazy" />',
			esc_url( angtagumpay_fallback_thumb( $fallback_index ) ),
			esc_attr( $alt ? $alt : get_the_title( $post_id ) )
		);
	}
}

/**
 * Estimated reading time, matching the "X min read" badges from the original design.
 */
function angtagumpay_reading_time( $post_id ) {
	$content   = get_post_field( 'post_content', $post_id );
	$word_count = str_word_count( wp_strip_all_tags( strip_shortcodes( $content ) ) );
	$minutes   = max( 1, (int) ceil( $word_count / 200 ) );
	return sprintf( _n( '%d min read', '%d min read', $minutes, 'ang-tagumpay' ), $minutes );
}

/**
 * Section/category badge label for a post (falls back to "Balita").
 */
function angtagumpay_badge_label( $post_id ) {
	$cats = get_the_category( $post_id );
	if ( ! empty( $cats ) ) {
		return esc_html( $cats[0]->name );
	}
	return __( 'Balita', 'ang-tagumpay' );
}

/**
 * Fallback primary menu if no menu has been assigned yet in Appearance > Menus,
 * so the site still looks right immediately after theme install.
 */
function angtagumpay_fallback_menu() {
	$links = array(
		array( 'label' => __( 'Home', 'ang-tagumpay' ), 'url' => home_url( '/' ) ),
		array( 'label' => __( 'Balita', 'ang-tagumpay' ), 'url' => angtagumpay_section_url( 'balita' ) ),
		array( 'label' => __( 'Opinyon', 'ang-tagumpay' ), 'url' => angtagumpay_section_url( 'opinyon' ) ),
		array( 'label' => __( 'Lathalain', 'ang-tagumpay' ), 'url' => angtagumpay_section_url( 'lathalain' ) ),
		array( 'label' => __( 'Isports', 'ang-tagumpay' ), 'url' => angtagumpay_section_url( 'isports' ) ),
		array( 'label' => __( 'Multimedia', 'ang-tagumpay' ), 'url' => ( is_front_page() ? '#multimedia' : home_url( '/#multimedia' ) ) ),
	);

	$about = get_page_by_path( 'about' );
	if ( ! $about ) {
		$about = get_page_by_path( 'tungkol-sa-amin' );
	}
	$links[] = array(
		'label' => __( 'Tungkol Sa Amin', 'ang-tagumpay' ),
		'url'   => $about ? get_permalink( $about ) : home_url( '/tungkol-sa-amin/' ),
	);

	echo '<ul id="mainMenu" class="nav__menu">';
	foreach ( $links as $link ) {
		$active = ( is_front_page() && $link['url'] === home_url( '/' ) ) ? ' class="active"' : '';
		printf( '<li><a%s href="%s">%s</a></li>', $active, esc_url( $link['url'] ), esc_html( $link['label'] ) );
	}
	echo '</ul>';
}

/**
 * Resolve a "section" (Balita/Opinyon/Lathalain/Isports) to a category link when a
 * matching category exists, otherwise to the homepage anchor.
 */
function angtagumpay_section_url( $slug ) {
	$term = get_category_by_slug( $slug );
	if ( $term ) {
		return get_category_link( $term );
	}
	return is_front_page() ? '#' . $slug : home_url( '/#' . $slug );
}

/**
 * Output the primary nav menu (theme menu if assigned, otherwise the fallback).
 */
function angtagumpay_primary_menu() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'items_wrap'     => '<ul id="mainMenu" class="nav__menu">%3$s</ul>',
			'fallback_cb'    => 'angtagumpay_fallback_menu',
		) );
	} else {
		angtagumpay_fallback_menu();
	}
}

/**
 * Create starter content (About page + Balita/Opinyon/Lathalain/Isports categories)
 * once, the first time the theme is activated.
 */
function angtagumpay_after_switch_theme() {
	$sections = array(
		'balita'    => 'Balita',
		'opinyon'   => 'Opinyon',
		'lathalain' => 'Lathalain',
		'isports'   => 'Isports',
	);
	foreach ( $sections as $slug => $name ) {
		if ( ! get_category_by_slug( $slug ) ) {
			wp_insert_term( $name, 'category', array( 'slug' => $slug ) );
		}
	}

	if ( ! get_page_by_path( 'tungkol-sa-amin' ) ) {
		$about_content  = "<p><strong>ANG TAGUMPAY</strong> ang opisyal na pampaaralang pahayagan ng Libertad National High School. Layunin nitong maghatid ng balita, opinyon, lathalain, isports, at multimedia content na malinaw, makabuluhan, at responsable.</p>";
		wp_insert_post( array(
			'post_title'   => 'Tungkol Sa Amin',
			'post_name'    => 'tungkol-sa-amin',
			'post_content' => $about_content,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'page_template' => 'page-templates/template-about.php',
		) );
	}
}
add_action( 'after_switch_theme', 'angtagumpay_after_switch_theme' );

/**
 * Point the theme's search form at real WordPress search instead of the
 * client-side filter used in the original static prototype.
 */
function angtagumpay_search_form( $form ) {
	return '<form class="search" role="search" method="get" action="' . esc_url( home_url( '/' ) ) . '">
		<input id="searchInput" type="search" name="s" value="' . get_search_query() . '" placeholder="Maghanap ng artikulo..." aria-label="Search articles" />
	</form>';
}
add_filter( 'get_search_form', 'angtagumpay_search_form' );
