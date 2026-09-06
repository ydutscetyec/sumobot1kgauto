<?php
/**
 * 404 error page.
 */
get_header();
?>

<main class="container page-content">
  <h1>404</h1>
  <p><?php esc_html_e( 'Hindi mahanap ang pahinang hinahanap mo. Baka natanggal na ito o mali ang link.', 'ang-bantayog' ); ?></p>
  <p><a href="<?php echo esc_url( home_url( '/' ) ); ?>">&larr; <?php esc_html_e( 'Bumalik sa Home', 'ang-bantayog' ); ?></a></p>
  <?php get_search_form(); ?>
</main>

<?php get_footer(); ?>
