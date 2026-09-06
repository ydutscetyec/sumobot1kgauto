  <footer class="footer">
    <div class="container footer__grid">
      <div>
        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/ang-bantayog-seal.png' ); ?>" alt="" class="footer__seal" />
        <h2><?php bloginfo( 'name' ); ?></h2>
        <p><?php echo esc_html( get_bloginfo( 'description', 'display' ) ? get_bloginfo( 'description', 'display' ) : __( 'Opisyal na Pampaaralang Pahayagan ng Libertad National High School.', 'ang-bantayog' ) ); ?></p>
      </div>
      <div>
        <h3><?php esc_html_e( 'Mga Seksyon', 'ang-bantayog' ); ?></h3>
        <a href="<?php echo esc_url( angbantayog_section_url( 'balita' ) ); ?>"><?php esc_html_e( 'Balita', 'ang-bantayog' ); ?></a>
        <a href="<?php echo esc_url( angbantayog_section_url( 'opinyon' ) ); ?>"><?php esc_html_e( 'Opinyon', 'ang-bantayog' ); ?></a>
        <a href="<?php echo esc_url( angbantayog_section_url( 'lathalain' ) ); ?>"><?php esc_html_e( 'Lathalain', 'ang-bantayog' ); ?></a>
        <a href="<?php echo esc_url( angbantayog_section_url( 'isports' ) ); ?>"><?php esc_html_e( 'Isports', 'ang-bantayog' ); ?></a>
        <a href="<?php echo esc_url( angbantayog_section_url( 'multimedia' ) ); ?>"><?php esc_html_e( 'Multimedia', 'ang-bantayog' ); ?></a>
      </div>
      <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
        <div><?php dynamic_sidebar( 'footer-1' ); ?></div>
      <?php endif; ?>
    </div>
    <div class="footer__bottom">&copy; <span id="year"><?php echo esc_html( date_i18n( 'Y' ) ); ?></span> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Lahat ng karapatan ay nakalaan.', 'ang-bantayog' ); ?></div>
  </footer>
</div><!-- #content -->

<button id="themeToggle" class="theme-toggle" aria-pressed="false" title="<?php esc_attr_e( 'Lumipat sa dark mode', 'ang-bantayog' ); ?>">
  <span class="theme-toggle__knob" aria-hidden="true">&#9728;</span>
  <span class="screen-reader-text"><?php esc_html_e( 'Palitan ang light o dark mode', 'ang-bantayog' ); ?></span>
</button>

<button id="backToTop" class="back-to-top" aria-label="<?php esc_attr_e( 'Bumalik sa itaas', 'ang-bantayog' ); ?>">&uarr;</button>

<?php wp_footer(); ?>
</body>
</html>
