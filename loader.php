<?php
/**
 * Ang Bantayog — intro loading overlay.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$spreads_uri = get_template_directory_uri() . '/assets/spreads';

/* file => label shown under the loading bar */
$ab_spreads = array(
	'1.jpg'  => 'Isports',
	'2.jpg'  => 'Isports',
	'3.jpg'  => 'Sports Feature',
	'4.jpg'  => 'Lathalain',
	'5.jpg'  => 'Lathalain',
	'6.jpg'  => 'Agham at Teknolohiya',
	'7.jpg'  => 'AGTEK',
	'8.jpg'  => 'Agham at Teknolohiya',
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
      <div class="ab-load__bar" aria-hidden="true"><i id="abLoadBar"></i></div>
    </div>
  </div>

</div>

<style id="ab-load-css">
.ab-load {
  position: fixed; inset: 0; z-index: 99999;
  background: #0d1b2a;
  overflow: hidden;
  transition: opacity .6s cubic-bezier(0.4, 0, 0.2, 1), visibility .6s ease;
}
.ab-load.is-done { opacity: 0; visibility: hidden; }

html.ab-load-lock, body.ab-load-lock { overflow: hidden; }

.ab-load__bg { 
  position: absolute; 
  inset: 0; 
  overflow: hidden;
  z-index: 1;
}

.ab-load__group {
  position: absolute; 
  inset: 0;
  opacity: 0;
  transition: opacity .6s cubic-bezier(0.4, 0, 0.2, 1);
  will-change: opacity;
}

.ab-load__fill {
  position: absolute; 
  inset: -8%;
  width: 116%; 
  height: 116%;
  object-fit: cover;
  filter: blur(40px) saturate(1.25);
  opacity: .85;
  transform: scale(1.05);
  animation: ab-load-kenburns 4s ease-out both;
}

@keyframes ab-load-kenburns {
  from { transform: scale(1.05); }
  to { transform: scale(1); }
}

.ab-load__row {
  position: absolute; 
  inset: 0;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2.5vw;
  padding: 8vh 5vw;
  align-items: center;
  z-index: 2;
}

.ab-load__row img {
  width: 100%; 
  height: auto;
  object-fit: contain;
  filter: blur(2px) saturate(1.1);
  opacity: 0;
  transform: translateY(20px) scale(0.98);
  animation: ab-load-reveal .7s cubic-bezier(0.4, 0, 0.2, 1) forwards;
  display: block;
}

.ab-load__row img:nth-child(1) { animation-delay: .1s; }
.ab-load__row img:nth-child(2) { animation-delay: .2s; }
.ab-load__row img:nth-child(3) { animation-delay: .3s; }

@keyframes ab-load-reveal {
  to { opacity: .95; transform: translateY(0) scale(1); }
}

.ab-load__veil {
  position: absolute; 
  inset: 0;
  background: radial-gradient(
    ellipse 55% 40% at center,
    rgba(13, 27, 42, .92),
    rgba(13, 27, 42, .65) 50%,
    rgba(13, 27, 42, .35) 100%
  );
  backdrop-filter: blur(2px);
  z-index: 3;
}

.ab-load__stage {
  position: absolute; 
  inset: 0;
  display: grid; 
  place-items: center;
  padding: 24px; 
  text-align: center;
  z-index: 4;
}

.ab-load__kicker {
  display: block;
  font-family: "Figtree", "Segoe UI", system-ui, -apple-system, Arial, sans-serif;
  font-size: clamp(.65rem, 1.8vw, .85rem);
  font-weight: 800; 
  letter-spacing: .65em; 
  text-transform: uppercase;
  color: #e1b64a; 
  margin-bottom: 4px;
  text-shadow: 0 2px 16px rgba(0,0,0,.95);
  animation: ab-load-fadeup .7s cubic-bezier(0.4, 0, 0.2, 1) both;
}

.ab-load__word {
  display: block;
  font-family: "Figtree", "Segoe UI", system-ui, -apple-system, Arial, sans-serif;
  font-size: clamp(3rem, 14vw, 9rem);
  font-weight: 900;
  line-height: .9; 
  letter-spacing: -.02em;
  color: #f6f4f2;
  text-shadow: 0 8px 40px rgba(0,0,0,.98), 0 2px 6px rgba(0,0,0,.9);
  animation: ab-load-fadeup .8s cubic-bezier(0.4, 0, 0.2, 1) .1s both;
}

.ab-load__rule {
  width: min(400px, 65vw); 
  height: 2px; 
  margin: 24px auto 0;
  background: linear-gradient(90deg, transparent, #c89434, transparent);
  box-shadow: 0 0 20px rgba(200, 148, 52, .4);
  animation: ab-load-fadeup .8s cubic-bezier(0.4, 0, 0.2, 1) .2s both;
}

@keyframes ab-load-fadeup {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}

.ab-load__bar {
  width: min(320px, 65vw); 
  height: 4px; 
  margin: 32px auto 0;
  background: rgba(246, 244, 242, .15);
  border-radius: 999px; 
  overflow: hidden;
  box-shadow: 0 2px 16px rgba(0,0,0,.6);
  animation: ab-load-fadeup .9s cubic-bezier(0.4, 0, 0.2, 1) .3s both;
}

.ab-load__bar i {
  display: block; 
  height: 100%; 
  width: 0%;
  background: linear-gradient(90deg, #c89434, #e1b64a);
  border-radius: 999px;
  box-shadow: 0 0 12px rgba(200, 148, 52, .6);
}

.ab-load__skip {
  position: absolute; 
  right: 20px; 
  top: 20px; 
  z-index: 5;
  padding: 8px 18px; 
  border-radius: 999px;
  border: 1px solid rgba(200, 148, 52, .4);
  background: rgba(13, 27, 42, .7);
  color: rgba(246, 244, 242, .9);
  font-family: "Figtree", "Segoe UI", system-ui, -apple-system, Arial, sans-serif;
  font-size: .6rem; 
  font-weight: 800;
  letter-spacing: .16em; 
  text-transform: uppercase;
  cursor: pointer;
  backdrop-filter: blur(8px);
  transition: all .25s ease;
}

.ab-load__skip:hover { 
  background: rgba(200, 148, 52, .2); 
  color: #f6f4f2;
  border-color: rgba(200, 148, 52, .8);
  transform: translateY(-1px);
}

.admin-bar .ab-load__skip { top: 52px; }

@media (max-width: 720px) {
  .ab-load__row { gap: 1.6vw; padding: 22vh 3vw; }
  .ab-load__row img { filter: blur(1.5px) saturate(1.1); }
  .ab-load__fill { filter: blur(32px) saturate(1.25); }
  .ab-load__veil {
    background: radial-gradient(
      ellipse 85% 38% at center,
      rgba(13, 27, 42, .94),
      rgba(13, 27, 42, .65) 55%,
      rgba(13, 27, 42, .35) 100%
    );
  }
  .ab-load__kicker { letter-spacing: .5em; }
  .ab-load__bar { height: 3px; }
}

@media (prefers-reduced-motion: reduce) {
  .ab-load { transition: opacity .3s ease; }
  .ab-load__group { transition: none; }
  .ab-load__fill, .ab-load__row img { animation: none; opacity: .95; }
  .ab-load__kicker, .ab-load__word, .ab-load__rule, .ab-load__bar { animation: none; opacity: 1; }
}
</style>

<script id="ab-load-js">
(function () {
  'use strict';

  var HOLD_MS          = 1000;
  var PER_ROW          = 3;
  var ONCE_PER_SESSION = false;

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

  if (seen()) {
    root.parentNode && root.parentNode.removeChild(root);
    return;
  }

  docEl.classList.add('ab-load-lock');
  document.body && document.body.classList.add('ab-load-lock');

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
    fill.onerror = function() { 
      console.log('Failed to load:', this.src);
      this.style.display = 'none';
    };
    group.appendChild(fill);

    var strip = document.createElement('div');
    strip.className = 'ab-load__row';
    row.forEach(function (s) {
      var img = document.createElement('img');
      img.src = BASE + '/' + s.src;
      img.alt = '';
      img.onerror = function() { 
        console.log('Failed to load row image:', this.src);
        this.style.opacity = '0.3';
      };
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

  setTimeout(start, 2500);
  setTimeout(dismiss, TOTAL_MS + 8000);
})();
</script>