<?php
/**
 * Footer: site footer, floating controls, and the small scripts that drive
 * them.
 *
 * The theme toggle and back-to-top button live here rather than in the
 * header so they float above whatever page is rendered. Both have styling
 * in style.css already (.theme-toggle, .back-to-top) — the markup below is
 * what those rules attach to.
 */
?>
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

      <!-- Online Publication Tungkol -->
      <div class="footer__publication">
        <img class="footer__publication-img"
             src="<?php echo esc_url( get_template_directory_uri() . '/assets/online-publication-tungkol.gif' ); ?>"
             alt="<?php esc_attr_e( 'Online Publication Tungkol', 'ang-bantayog' ); ?>"
             loading="lazy" />
      </div>
    </div>
    <div class="footer__bottom">&copy; <span id="year"><?php echo esc_html( date_i18n( 'Y' ) ); ?></span> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Lahat ng karapatan ay nakalaan.', 'ang-bantayog' ); ?></div>
  </footer>

  <!-- Floating controls. The toggle's two labels ("Light" / "Dark") come
       from ::before and ::after in style.css; the span below is the sliding
       highlight that sits behind whichever one is active. -->
  <button class="theme-toggle" id="themeToggle"
          aria-label="<?php esc_attr_e( 'Palitan ang light at dark mode', 'ang-bantayog' ); ?>">
    <span class="theme-toggle__knob" aria-hidden="true"></span>
  </button>

  <button class="back-to-top" id="backToTop"
          aria-label="<?php esc_attr_e( 'Bumalik sa itaas', 'ang-bantayog' ); ?>">&#8593;</button>

  <script>
  (function () {
    var root = document.documentElement;

    /* ---- Theme ----------------------------------------------------------
       data-theme goes on <html>, because style.css targets
       [data-theme="dark"] at the root. Setting it on <body> looks correct in
       devtools but matches none of the rules.

       header.php should also read localStorage before the stylesheet loads,
       otherwise a dark-mode reader gets a white flash on every page load.
       -------------------------------------------------------------------- */
    var saved = localStorage.getItem('ab-theme');
    if (saved === 'dark' || saved === 'light') {
      root.setAttribute('data-theme', saved);
    }

    var toggle = document.getElementById('themeToggle');
    if (toggle) {
      toggle.addEventListener('click', function () {
        var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        root.setAttribute('data-theme', next);
        localStorage.setItem('ab-theme', next);
      });
    }

    /* ---- Back to top --------------------------------------------------- */
    var top = document.getElementById('backToTop');
    if (top) {
      window.addEventListener('scroll', function () {
        top.classList.toggle('visible', window.scrollY > 400);
      }, { passive: true });

      top.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }

    /* ---- Balita tabs --------------------------------------------------- */
    function init() {
      document.querySelectorAll('.balita-tabs').forEach(function (tabs) {
        var buttons = tabs.querySelectorAll('.balita-tabs__btn');
        var panels  = tabs.querySelectorAll('.balita-tabs__panel');

        buttons.forEach(function (btn, idx) {
          btn.addEventListener('click', function () {
            buttons.forEach(function (b) { b.classList.remove('is-active'); });
            panels.forEach(function (p) { p.classList.remove('is-active'); p.hidden = true; });

            btn.classList.add('is-active');
            if (panels[idx]) {
              panels[idx].classList.add('is-active');
              panels[idx].hidden = false;
            }
          });
        });
      });
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', init);
    } else {
      init();
    }
  })();
  </script>

  <script>
  (function () {
    var KEY = 'angbantayog-theme';

    function applyTheme(theme) {
      var nodes = [document.documentElement, document.body];
      for (var i = 0; i < nodes.length; i++) {
        if (!nodes[i]) continue;
        if (theme === 'dark') nodes[i].setAttribute('data-theme', 'dark');
        else nodes[i].removeAttribute('data-theme');
      }
    }

    /* Apply saved choice on every page load */
    try {
      var saved = localStorage.getItem(KEY);
      if (saved) applyTheme(saved);
    } catch (e) {}

    function init() {
      var btn = document.getElementById('themeToggle');
      if (!btn) return;

      /* Cloning wipes ALL handlers the old script attached */
      var clone = btn.cloneNode(true);
      btn.parentNode.replaceChild(clone, btn);

      clone.addEventListener('click', function (e) {
        e.stopPropagation(); /* blocks any document-level handlers too */
        var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        var next = isDark ? 'light' : 'dark';
        applyTheme(next);
        try { localStorage.setItem(KEY, next); } catch (err) {}
        clone.setAttribute('aria-pressed', isDark ? 'false' : 'true');
      });
    }

    /* Run AFTER the old script has attached, then double-check once more */
    window.addEventListener('load', function () {
      init();
      setTimeout(init, 400);
    });
  })();
  </script>

  <?php wp_footer(); ?>
</body>
</html>