<?php
/**
 * Ang Bantayog — intro loading overlay.
 *
 * Sits on top of the site, plays a short sequence of blurred spreads
 * behind the wordmark, then fades away to reveal the page underneath.
 * No redirect: the real page is already loading behind it.
 *
 * Include from header.php, right after wp_body_open():
 *     <?php get_template_part( 'loader' ); ?>
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$spreads_uri = get_template_directory_uri() . '/assets/spreads';

/* file => label shown under the loading bar */
$ab_spreads = array(
	'01-isports-cover.jpg'           => 'Isports',
	'02-isports-paragames.jpg'       => 'Isports',
	'03-isports-takbong-tapang.jpg'  => 'Sports Feature',
	'04-lathalain-late-night.jpg'    => 'Lathalain',
	'05-lathalain-huling-baraha.jpg' => 'Lathalain',
	'06-agtek-hivisionary.jpg'       => 'Agham at Teknolohiya',
	'07-agtek-spread.jpg'            => 'AGTEK',
	'08-agtek-diwang-di-pipi.jpg'    => 'Agham at Teknolohiya',
);
?>

<div class="ab-load" id="abLoad" role="status" aria-live="polite" aria-label="Naglo-load ang Ang Bantayog">

  <div class="ab-load__bg" id="abLoadBg" aria-hidden="true">
    <div class="ab-load__veil"></div>
  </div>

  <button type="button" class="ab-load__skip" id="abLoadSkip">Laktawan</button>

  <div class="ab-load__stage">
    <div>
      <span class="ab-load__kicker">Ang</span>
      <span class="ab-load__word">BANTAYOG</span>
      <div class="ab-load__rule" aria-hidden="true"></div>
      <p class="ab-load__tagline"><?php bloginfo( 'description' ); ?></p>
      <div class="ab-load__bar" aria-hidden="true"><i id="abLoadBar"></i></div>
      <p class="ab-load__status" id="abLoadStatus">Isports</p>
    </div>
  </div>

</div>

<style id="ab-load-css">
/* ==========================================================================
   Intro loading overlay
   Scoped under .ab-load so nothing here can leak into the site's own styles.

   Colours are hardcoded rather than read from the theme's custom properties
   on purpose: the overlay is a splash screen and should look identical in
   light and dark mode, and it paints before any mode class is guaranteed.
   Palette matches the theme tokens — teal #17708a, deep #0d3d4b,
   aqua #4fbfb8, aqua-lit #8febe0, pale #f2fbfa.
   ========================================================================== */
.ab-load {
  position: fixed; inset: 0; z-index: 99999;
  background: #0a2e39;
  overflow: hidden;
  transition: opacity .5s ease, visibility .5s ease;
}
.ab-load.is-done { opacity: 0; visibility: hidden; }

/* Lock scrolling only while the overlay is up. */
html.ab-load-lock, body.ab-load-lock { overflow: hidden; }

.ab-load__bg { position: absolute; inset: 0; overflow: hidden; }

.ab-load__group {
  position: absolute; inset: 0;
  opacity: 0;
  transition: opacity .55s ease;
  will-change: opacity;
}

.ab-load__fill {
  position: absolute; inset: -8%;
  width: 116%; height: 116%;
  object-fit: cover;
  filter: blur(38px) saturate(1.2);
  opacity: .8;
}

.ab-load__row {
  position: absolute; inset: 0;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2.2vw;
  padding: 7vh 4vw;
  align-items: center;
}
.ab-load__row img {
  width: 100%; height: 100%;
  object-fit: contain;
  filter: blur(3px) saturate(1.05);
  opacity: .95;
  animation: ab-load-drift .8s ease-out both;
}
.ab-load__row img:nth-child(2) { animation-delay: .07s; }
.ab-load__row img:nth-child(3) { animation-delay: .14s; }

@keyframes ab-load-drift {
  from { opacity: 0; transform: scale(1.06) translateY(10px); }
  to   { opacity: .95; transform: scale(1) translateY(0); }
}

/* Dark where the wordmark sits, clearing toward the edges so the row
   stays visible. Inverse of a normal vignette, on purpose. */
.ab-load__veil {
  position: absolute; inset: 0;
  background: radial-gradient(
    ellipse 58% 44% at center,
    rgba(8, 26, 31, .9),
    rgba(8, 26, 31, .6) 55%,
    rgba(8, 26, 31, .3) 100%
  );
}

.ab-load__stage {
  position: absolute; inset: 0;
  display: grid; place-items: center;
  padding: 24px; text-align: center;
}

.ab-load__kicker {
  display: block;
  font-family: "Figtree", "Segoe UI", system-ui, -apple-system, Arial, sans-serif;
  font-size: clamp(.6rem, 1.6vw, .82rem);
  font-weight: 800; letter-spacing: .62em; text-transform: uppercase;
  color: #8febe0; margin-bottom: 2px; text-indent: .62em;
  text-shadow: 0 2px 12px rgba(0,0,0,.95);
  animation: ab-load-rise .55s ease-out both;
}

/* Matches the site wordmark: same family, same 900 weight, same tight
   tracking — the splash and the masthead should read as one identity. */
.ab-load__word {
  display: block;
  font-family: "Figtree", "Segoe UI", system-ui, -apple-system, Arial, sans-serif;
  font-size: clamp(2.6rem, 13vw, 8.4rem);
  font-weight: 900;
  line-height: .92; letter-spacing: -.025em;
  color: #f2fbfa;
  text-shadow: 0 4px 30px rgba(0,0,0,.98), 0 1px 3px rgba(0,0,0,.9);
  animation: ab-load-rise .7s ease-out both;
}
.ab-load__rule {
  width: min(360px, 62vw); height: 1px; margin: 20px auto 0;
  background: linear-gradient(90deg, transparent, rgba(79, 191, 184, .8), transparent);
  animation: ab-load-rise .78s ease-out both;
}
.ab-load__tagline {
  margin: 14px 0 0;
  font-family: "Figtree", "Segoe UI", system-ui, -apple-system, Arial, sans-serif;
  font-size: clamp(.72rem, 1.9vw, .9rem);
  color: rgba(242, 251, 250, .82);
  text-shadow: 0 2px 12px rgba(0,0,0,.95);
  animation: ab-load-rise .86s ease-out both;
}

@keyframes ab-load-rise {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}

.ab-load__bar {
  width: min(300px, 62vw); height: 3px; margin: 28px auto 0;
  background: rgba(242, 251, 250, .2);
  border-radius: 999px; overflow: hidden;
  box-shadow: 0 2px 12px rgba(0,0,0,.7);
}
.ab-load__bar i {
  display: block; height: 100%; width: 0%;
  background: linear-gradient(90deg, #4fbfb8, #8febe0);
  border-radius: 999px;
}

.ab-load__status {
  margin: 12px 0 0; height: 1.2em;
  font-family: "Figtree", "Segoe UI", system-ui, -apple-system, Arial, sans-serif;
  font-size: .62rem; font-weight: 800;
  letter-spacing: .24em; text-transform: uppercase;
  color: #8febe0;
  text-shadow: 0 2px 10px rgba(0,0,0,.95);
}

.ab-load__skip {
  position: absolute; right: 18px; top: 14px; z-index: 2;
  padding: 7px 15px; border-radius: 999px;
  border: 1px solid rgba(79, 191, 184, .5);
  background: rgba(8, 26, 31, .62);
  color: rgba(242, 251, 250, .85);
  font-family: "Figtree", "Segoe UI", system-ui, -apple-system, Arial, sans-serif;
  font-size: .56rem; font-weight: 800;
  letter-spacing: .14em; text-transform: uppercase;
  cursor: pointer;
  transition: background-color .2s ease, color .2s ease;
}
.ab-load__skip:hover { background: #17708a; color: #f2fbfa; }

/* Logged-in admin bar sits above everything; nudge the button clear of it. */
.admin-bar .ab-load__skip { top: 46px; }

@media (max-width: 720px) {
  .ab-load__row { gap: 1.4vw; padding: 20vh 2.5vw; }
  .ab-load__row img { filter: blur(1.8px) saturate(1.05); }
  .ab-load__fill { filter: blur(28px) saturate(1.2); }
  .ab-load__veil {
    background: radial-gradient(
      ellipse 82% 40% at center,
      rgba(8, 26, 31, .92),
      rgba(8, 26, 31, .6) 60%,
      rgba(8, 26, 31, .3) 100%
    );
  }
  .ab-load__kicker { letter-spacing: .48em; text-indent: .48em; }
}

@media (prefers-reduced-motion: reduce) {
  .ab-load__group { transition: none; }
  .ab-load__row img { animation: none; opacity: .95; }
  .ab-load__kicker, .ab-load__word, .ab-load__rule, .ab-load__tagline { animation: none; }
}
</style>

<script id="ab-load-js">
(function () {
  'use strict';

  /* ------------------------------------------------------------------
     CONFIG
     ------------------------------------------------------------------ */
  var HOLD_MS          = 900;   // how long each row of three stays on screen
  var PER_ROW          = 3;     // spreads side by side per step
  var ONCE_PER_SESSION = false; // true = only on the first page of a visit

  var BASE = <?php echo wp_json_encode( $spreads_uri ); ?>;
  var SPREADS = <?php
    $out = array();
    foreach ( $ab_spreads as $file => $label ) {
      $out[] = array( 'src' => $file, 'label' => $label );
    }
    echo wp_json_encode( $out );
  ?>;

  var root   = document.getElementById('abLoad');
  var bg     = document.getElementById('abLoadBg');
  var veil   = bg.querySelector('.ab-load__veil');
  var bar    = document.getElementById('abLoadBar');
  var status = document.getElementById('abLoadStatus');
  var skip   = document.getElementById('abLoadSkip');
  var docEl  = document.documentElement;

  var SEEN_KEY = 'ab-load-seen';

  function seen() {
    if (!ONCE_PER_SESSION) return false;
    try { return sessionStorage.getItem(SEEN_KEY) === '1'; } catch (e) { return false; }
  }
  function markSeen() {
    try { sessionStorage.setItem(SEEN_KEY, '1'); } catch (e) {}
  }

  /* Already shown this session — take the overlay down before it paints. */
  if (seen()) {
    root.parentNode && root.parentNode.removeChild(root);
    return;
  }

  docEl.classList.add('ab-load-lock');
  document.body && document.body.classList.add('ab-load-lock');

  /* Chunk into rows. The final row wraps back to the start rather than
     running short, so every step is a full row. */
  var rows = [];
  for (var i = 0; i < SPREADS.length; i += PER_ROW) {
    var row = [];
    for (var n = 0; n < PER_ROW; n++) row.push(SPREADS[(i + n) % SPREADS.length]);
    rows.push(row);
  }

  var TOTAL_MS = HOLD_MS * rows.length;

  var groups = rows.map(function (row) {
    var group = document.createElement('div');
    group.className = 'ab-load__group';

    var fill = document.createElement('img');
    fill.className = 'ab-load__fill';
    fill.src = BASE + '/' + row[0].src;
    fill.alt = '';
    group.appendChild(fill);

    var strip = document.createElement('div');
    strip.className = 'ab-load__row';
    row.forEach(function (s) {
      var img = document.createElement('img');
      img.src = BASE + '/' + s.src;
      img.alt = '';
      strip.appendChild(img);
    });
    group.appendChild(strip);

    bg.insertBefore(group, veil);
    return group;
  });

  var started  = 0;
  var shownIdx = -1;
  var finished = false;

  function show(i) {
    if (i === shownIdx) return;
    shownIdx = i;
    groups.forEach(function (g, n) { g.style.opacity = (n === i) ? 1 : 0; });

    var imgs = groups[i] ? groups[i].querySelectorAll('.ab-load__row img') : [];
    Array.prototype.forEach.call(imgs, function (img) {
      img.style.animation = 'none';
      void img.offsetWidth;
      img.style.animation = '';
    });

    if (rows[i] && rows[i][0]) status.textContent = rows[i][0].label;
  }

  function frame(now) {
    var elapsed = now - started;
    var p = Math.min(elapsed / TOTAL_MS, 1);

    bar.style.width = (p * 100).toFixed(1) + '%';
    show(Math.min(Math.floor(elapsed / HOLD_MS), rows.length - 1));

    if (p < 1) requestAnimationFrame(frame);
    else dismiss();
  }

  function dismiss() {
    if (finished) return;
    finished = true;
    markSeen();
    root.classList.add('is-done');
    docEl.classList.remove('ab-load-lock');
    document.body && document.body.classList.remove('ab-load-lock');
    setTimeout(function () {
      root.parentNode && root.parentNode.removeChild(root);
    }, 600);
  }

  skip.addEventListener('click', dismiss);
  window.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') dismiss();
  });

  var first  = groups[0] ? groups[0].querySelector('.ab-load__row img') : null;
  var kicked = false;

  function start() {
    if (kicked) return;
    kicked = true;
    show(0);
    started = performance.now();
    requestAnimationFrame(frame);
  }

  if (first && first.complete) start();
  else if (first) {
    first.addEventListener('load', start);
    first.addEventListener('error', start);
  } else start();

  /* Hard caps: never let a slow image hold the sequence up, and never let
     anything trap a visitor behind the overlay. */
  setTimeout(start, 2500);
  setTimeout(dismiss, TOTAL_MS + 8000);
})();
</script>