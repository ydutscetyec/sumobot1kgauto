<?php
/**
 * Ang Bantayog theme functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ANGBANTAYOG_VERSION', '4.7' );

/**
 * Theme setup
 */
function angbantayog_setup() {
	load_theme_textdomain( 'ang-bantayog', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'customize-selective-refresh-widgets' );

	set_post_thumbnail_size( 800, 500, true );
	add_image_size( 'angbantayog-featured', 1100, 620, true );
	add_image_size( 'angbantayog-wide', 700, 500, true );

	register_nav_menus( array(
		'primary' => __( 'Pangunahing Menu (Primary)', 'ang-bantayog' ),
	) );
}
add_action( 'after_setup_theme', 'angbantayog_setup' );

/**
 * Webfont — Figtree.
 */
function angbantayog_fonts() {
	wp_enqueue_style(
		'angbantayog-figtree',
		'https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,400;0,600;0,800;0,900;1,400&display=swap',
		array(),
		null
	);
}
add_action( 'wp_enqueue_scripts', 'angbantayog_fonts', 5 );

/**
 * Resource hints for the font host.
 */
function angbantayog_font_preconnect( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => '' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'angbantayog_font_preconnect', 10, 2 );

/**
 * Enqueue styles and scripts
 */
function angbantayog_scripts() {
	wp_enqueue_style( 'ang-bantayog-style', get_stylesheet_uri(), array(), ANGBANTAYOG_VERSION );
	wp_enqueue_script( 'ang-bantayog-script', get_template_directory_uri() . '/script.js', array(), ANGBANTAYOG_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'angbantayog_scripts' );

/**
 * Register widget area
 */
function angbantayog_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer', 'ang-bantayog' ),
		'id'            => 'footer-1',
		'description'   => __( 'Widgets dito ay lalabas sa footer.', 'ang-bantayog' ),
		'before_widget' => '<div class="footer-widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'angbantayog_widgets_init' );

/**
 * Fallback thumbnails
 */
function angbantayog_fallback_thumb( $index = 0 ) {
	$fallbacks = array(
		'article-1.svg', 'article-2.svg', 'article-3.svg',
		'article-4.svg', 'article-5.svg', 'article-6.svg',
	);
	$file = $fallbacks[ $index % count( $fallbacks ) ];
	return get_template_directory_uri() . '/assets/' . $file;
}

/**
 * Print a post's thumbnail
 */
function angbantayog_post_thumb( $post_id, $size = 'angbantayog-featured', $fallback_index = 0, $alt = '' ) {
	if ( has_post_thumbnail( $post_id ) ) {
		echo get_the_post_thumbnail( $post_id, $size, array( 'alt' => esc_attr( $alt ) ) );
	} else {
		printf(
			'<img src="%s" alt="%s" loading="lazy" />',
			esc_url( angbantayog_fallback_thumb( $fallback_index ) ),
			esc_attr( $alt ? $alt : get_the_title( $post_id ) )
		);
	}
}

/**
 * Estimated reading time
 */
function angbantayog_reading_time( $post_id ) {
	$content    = get_post_field( 'post_content', $post_id );
	$word_count = str_word_count( wp_strip_all_tags( strip_shortcodes( $content ) ) );
	$minutes    = max( 1, (int) ceil( $word_count / 200 ) );
	return sprintf( _n( '%d min read', '%d min read', $minutes, 'ang-bantayog' ), $minutes );
}

/**
 * Get ALL category labels for a post (excluding Uncategorized).
 * Returns an array of names so we can print multiple tags.
 */
function angbantayog_badge_labels( $post_id ) {
	$cats = get_the_category( $post_id );
	$labels = array();

	foreach ( $cats as $cat ) {
		if ( 'uncategorized' !== $cat->slug ) {
			$labels[] = esc_html( $cat->name );
		}
	}

	// Fallback to 'Balita' if no valid categories are found
	return ! empty( $labels ) ? $labels : array( __( 'Balita', 'ang-bantayog' ) );
}

/**
 * Fallback primary menu
 */
function angbantayog_fallback_menu() {
	$links = array(
		array( 'label' => __( 'Home', 'ang-bantayog' ), 'url' => home_url( '/' ) ),
		array( 'label' => __( 'Balita', 'ang-bantayog' ), 'url' => angbantayog_section_url( 'balita' ) ),
		array( 'label' => __( 'Opinyon', 'ang-bantayog' ), 'url' => angbantayog_section_url( 'opinyon' ) ),
		array( 'label' => __( 'Lathalain', 'ang-bantayog' ), 'url' => angbantayog_section_url( 'lathalain' ) ),
		array( 'label' => __( 'Isports', 'ang-bantayog' ), 'url' => angbantayog_section_url( 'isports' ) ),
		array( 'label' => __( 'Multimedia', 'ang-bantayog' ), 'url' => angbantayog_section_url( 'multimedia' ) ),
	);

	$about = get_page_by_path( 'about' );
	if ( ! $about ) {
		$about = get_page_by_path( 'tungkol-sa-amin' );
	}
	$links[] = array(
		'label' => __( 'Tungkol Sa Amin', 'ang-bantayog' ),
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
 * Resolve a "section" to a category link
 */
function angbantayog_section_url( $slug ) {
	if ( 'multimedia' === $slug ) {
		$page = get_page_by_path( 'multimedia' );
		if ( $page ) {
			return get_permalink( $page );
        }
    }
    $term = get_category_by_slug( $slug );
    if ( $term ) {
        return get_category_link( $term );
    }
    return is_front_page() ? '#' . $slug : home_url( '/#' . $slug );
}

/**
 * Output the primary nav menu
 */
function angbantayog_primary_menu() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'items_wrap'     => '<ul id="mainMenu" class="nav__menu">%3$s</ul>',
			'fallback_cb'    => 'angbantayog_fallback_menu',
		) );
	} else {
		angbantayog_fallback_menu();
	}
}

/**
 * Create starter content once
 */
function angbantayog_after_switch_theme() {
	$sections = array(
		'balita'     => 'Balita',
		'opinyon'    => 'Opinyon',
		'lathalain'  => 'Lathalain',
		'isports'    => 'Isports',
		'multimedia' => 'Multimedia',
	);
	foreach ( $sections as $slug => $name ) {
		if ( ! get_category_by_slug( $slug ) ) {
			wp_insert_term( $name, 'category', array( 'slug' => $slug ) );
		}
	}

	if ( ! get_page_by_path( 'tungkol-sa-amin' ) ) {
		$about_content = "<p><strong>ANG BANTAYOG</strong> ang opisyal na pampaaralang pahayagan ng Libertad National High School. Layunin nitong maghatid ng balita, opinyon, lathalain, isports, at multimedia content na malinaw, makabuluhan, at responsable.</p>";
		wp_insert_post( array(
			'post_title'    => 'Tungkol Sa Amin',
			'post_name'     => 'tungkol-sa-amin',
			'post_content'  => $about_content,
			'post_status'   => 'publish',
			'post_type'     => 'page',
			'page_template' => 'page-templates/template-about.php',
		) );
	}
}
add_action( 'after_switch_theme', 'angbantayog_after_switch_theme' );

/* -------------------------------------------------------------------------
   Homepage queries
   ------------------------------------------------------------------------- */

function angbantayog_feed( $count, $exclude = array(), $args = array() ) {
	return new WP_Query( wp_parse_args( $args, array(
		'posts_per_page'      => $count,
		'post__not_in'        => $exclude,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	) ) );
}

function angbantayog_section_query( $slug, $count, $exclude = array() ) {
	return angbantayog_feed( $count, $exclude, array( 'category_name' => $slug ) );
}

function angbantayog_popular( $count, $exclude = array() ) {
	return angbantayog_feed( $count, $exclude, array(
		'orderby' => array( 'comment_count' => 'DESC', 'date' => 'DESC' ),
	) );
}

/**
 * Apply the reader's saved colour mode before the page paints.
 */
function angbantayog_theme_mode_boot() {
	?>
	<script>
	(function () {
		try {
			var saved = localStorage.getItem('angbantayog-theme');
			var dark = saved ? saved === 'dark'
				: (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
			document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
		} catch (e) {}
	})();
	</script>
	<?php
}
add_action( 'wp_head', 'angbantayog_theme_mode_boot', 1 );

/**
 * Point the theme's search form at real WordPress search
 */
function angbantayog_search_form( $form ) {
	return '<form class="search" role="search" method="get" action="' . esc_url( home_url( '/' ) ) . '">
		<input id="searchInput" type="search" name="s" value="' . get_search_query() . '" placeholder="Maghanap ng artikulo" aria-label="Search articles" />
	</form>';
}
add_filter( 'get_search_form', 'angbantayog_search_form' );

/* -------------------------------------------------------------------------
   Relative timestamps
   ------------------------------------------------------------------------- */
function angbantayog_relative_date( $the_date, $format, $post ) {
	if ( is_admin() || ! $post instanceof WP_Post ) {
		return $the_date;
	}
	if ( is_feed() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return $the_date;
	}

	$posted = get_post_time( 'U', true, $post );
	$now    = current_time( 'timestamp', true );
	$diff   = $now - $posted;

	if ( $diff < 0 || $diff > WEEK_IN_SECONDS ) {
		return $the_date;
	}
	if ( $diff < MINUTE_IN_SECONDS ) {
		return __( 'Ngayon lang', 'ang-bantayog' );
	}
	if ( $diff < HOUR_IN_SECONDS ) {
		$mins = (int) floor( $diff / MINUTE_IN_SECONDS );
		return sprintf( _n( '%d minuto ang nakalipas', '%d minuto ang nakalipas', $mins, 'ang-bantayog' ), $mins );
	}
	if ( $diff < DAY_IN_SECONDS ) {
		$hours = (int) floor( $diff / HOUR_IN_SECONDS );
		return sprintf( _n( '%d oras ang nakalipas', '%d oras ang nakalipas', $hours, 'ang-bantayog' ), $hours );
	}
	$days = (int) floor( $diff / DAY_IN_SECONDS );
	if ( 1 === $days ) {
		return __( 'Kahapon', 'ang-bantayog' );
	}
	return sprintf( _n( '%d araw ang nakalipas', '%d araw ang nakalipas', $days, 'ang-bantayog' ), $days );
}
add_filter( 'get_the_date', 'angbantayog_relative_date', 10, 3 );

/* -------------------------------------------------------------------------
   Archive heading
   ------------------------------------------------------------------------- */
function angbantayog_archive_title( $title ) {
	if ( is_category() ) {
		return single_cat_title( '', false );
	}
	if ( is_tag() ) {
		return single_tag_title( '', false );
	}
	if ( is_author() ) {
		return get_the_author();
	}
	if ( is_search() ) {
		return sprintf( __( 'Resulta para sa &ldquo;%s&rdquo;', 'ang-bantayog' ), get_search_query() );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'angbantayog_archive_title' );

/* -------------------------------------------------------------------------
   Section body class on single posts
   ------------------------------------------------------------------------- */
function angbantayog_section_body_class( $classes ) {
	if ( ! is_singular( 'post' ) ) {
		return $classes;
	}
	$cats = get_the_category();
	if ( empty( $cats ) ) {
		return $classes;
	}
	$classes[] = 'section-' . sanitize_html_class( $cats[0]->slug );
	return $classes;
}
add_filter( 'body_class', 'angbantayog_section_body_class' );

/* ==========================================================================
   Homepage Sections Selector (5 Checkboxes)
   ========================================================================== */

function angbantayog_add_homepage_meta_boxes() {
    add_meta_box(
        'angbantayog_homepage_sections',
        __( 'Homepage Sections', 'ang-bantayog' ),
        'angbantayog_homepage_meta_box_callback',
        'post',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'angbantayog_add_homepage_meta_boxes' );

function angbantayog_homepage_meta_box_callback( $post ) {
    wp_nonce_field( 'angbantayog_homepage_sections', 'angbantayog_homepage_nonce' );
    
    $is_pangunahing = get_post_meta( $post->ID, '_angbantayog_pangunahing', true );
    $is_pinili = get_post_meta( $post->ID, '_angbantayog_pinili', true );
    $is_napapanahon = get_post_meta( $post->ID, '_angbantayog_napapanahon', true );
    $is_laganap = get_post_meta( $post->ID, '_angbantayog_laganap', true );
    $is_sikat = get_post_meta( $post->ID, '_angbantayog_sikat', true );
    ?>
    
    <p style="border-bottom:1px solid #ddd; padding-bottom:10px; margin-bottom:10px;">
        <label style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
            <input type="checkbox" name="angbantayog_pangunahing" value="1" <?php checked( $is_pangunahing, '1' ); ?> />
            <span>
                <strong><?php esc_html_e( 'Pangunahing Istorya', 'ang-bantayog' ); ?></strong>
                <br><small style="color:#666;"><?php esc_html_e( 'Hero slider (3 max)', 'ang-bantayog' ); ?></small>
            </span>
        </label>
        
        <label style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" name="angbantayog_pinili" value="1" <?php checked( $is_pinili, '1' ); ?> />
            <span>
                <strong><?php esc_html_e( 'Pinili ng Editor', 'ang-bantayog' ); ?></strong>
                <br><small style="color:#666;"><?php esc_html_e( 'Editor picks', 'ang-bantayog' ); ?></small>
            </span>
        </label>
    </p>
    
    <p style="border-bottom:1px solid #ddd; padding-bottom:10px; margin-bottom:10px;">
        <label style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
            <input type="checkbox" name="angbantayog_napapanahon" value="1" <?php checked( $is_napapanahon, '1' ); ?> />
            <span>
                <strong><?php esc_html_e( 'Napapanahon', 'ang-bantayog' ); ?></strong>
                <br><small style="color:#666;"><?php esc_html_e( 'Sidebar tab (Recent)', 'ang-bantayog' ); ?></small>
            </span>
        </label>
        
        <label style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" name="angbantayog_laganap" value="1" <?php checked( $is_laganap, '1' ); ?> />
            <span>
                <strong><?php esc_html_e( 'Laganap', 'ang-bantayog' ); ?></strong>
                <br><small style="color:#666;"><?php esc_html_e( 'Sidebar tab (Trending)', 'ang-bantayog' ); ?></small>
            </span>
        </label>
    </p>

    <p>
        <label style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" name="angbantayog_sikat" value="1" <?php checked( $is_sikat, '1' ); ?> />
            <span>
                <strong><?php esc_html_e( 'Kuwentong Sikat', 'ang-bantayog' ); ?></strong>
                <br><small style="color:#666;"><?php esc_html_e( 'Ranked list (5 max)', 'ang-bantayog' ); ?></small>
            </span>
        </label>
    </p>
    
    <?php
}

function angbantayog_save_homepage_meta( $post_id ) {
    if ( ! isset( $_POST['angbantayog_homepage_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['angbantayog_homepage_nonce'], 'angbantayog_homepage_sections' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = array( 'pangunahing', 'pinili', 'napapanahon', 'laganap', 'sikat' );
    foreach ( $fields as $field ) {
        if ( isset( $_POST['angbantayog_' . $field] ) ) {
            update_post_meta( $post_id, '_angbantayog_' . $field, '1' );
        } else {
            delete_post_meta( $post_id, '_angbantayog_' . $field );
        }
    }
}
add_action( 'save_post', 'angbantayog_save_homepage_meta' );


function ang_bantayog_audio_player_scripts() {
    // Only load on single post pages
    if (is_single()) {
        // Enqueue the CSS
        wp_enqueue_style(
            'audio-player-style',
            get_template_directory_uri() . '/audio-player.css',
            array(),
            '1.0.0'
        );
        
        // Enqueue the JS
        wp_enqueue_script(
            'audio-player-script',
            get_template_directory_uri() . '/audio-player.js',
            array(),
            '1.0.0',
            true // Load in footer
        );
    }
}
add_action('wp_enqueue_scripts', 'ang_bantayog_audio_player_scripts');

/**
 * 1. Load Audio Player CSS and JS on Single Posts
 */
function ang_bantayog_load_audio_assets() {
    if ( is_single() ) {
        // Load CSS
        wp_enqueue_style( 
            'audio-player-style', 
            get_template_directory_uri() . '/audio-player.css', 
            array(), 
            '1.0.0' 
        );
        
        // Load JS (in footer)
        wp_enqueue_script( 
            'audio-player-script', 
            get_template_directory_uri() . '/audio-player.js', 
            array(), 
            '1.0.0', 
            true 
        );
    }
}
add_action( 'wp_enqueue_scripts', 'ang_bantayog_load_audio_assets' );


/**
 * 2. Automatically Inject Audio Player HTML into Post Content
 */
function ang_bantayog_auto_inject_player( $content ) {
    // Check if it's a single post, inside the loop, and the main query
    if ( is_single() && in_the_loop() && is_main_query() ) {
        
        // The HTML for the player
        $player_html = '<div id="audioPlayer" style="margin-bottom: 30px;"></div>';
        
        // Add the player BEFORE the content
        return $player_html . $content;
    }
    
    return $content;
}
add_filter( 'the_content', 'ang_bantayog_auto_inject_player' );

function ang_bantayog_responsivevoice() {
    if (is_single()) {
        wp_enqueue_script('responsivevoice', 'https://code.responsivevoice.org/responsivevoice.js', array(), null, true);
    }
}
add_action('wp_enqueue_scripts', 'ang_bantayog_responsivevoice');