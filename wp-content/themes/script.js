/**
 * Ang Bantayog front-end behaviour.
 * Theme mode, headline ticker, section sliders, sidebar tabs, nav, back-to-top.
 */
(function () {
  'use strict';

  var root = document.documentElement;
  var STORAGE_KEY = 'angbantayog-theme';

  /* ---------- Light / dark mode ------------------------------------------ */
  function applyTheme(mode) {
    root.setAttribute('data-theme', mode);
    var btn = document.querySelector('#themeToggle');
    if (btn) {
      var dark = mode === 'dark';
      btn.setAttribute('aria-pressed', String(dark));
      btn.setAttribute('title', dark ? 'Lumipat sa light mode' : 'Lumipat sa dark mode');
      var knob = btn.querySelector('.theme-toggle__knob');
      if (knob) knob.textContent = dark ? '☾' : '☀';
    }
  }

  function storedTheme() {
    try { return localStorage.getItem(STORAGE_KEY); } catch (e) { return null; }
  }

  function saveTheme(mode) {
    try { localStorage.setItem(STORAGE_KEY, mode); } catch (e) {}
  }

  var systemDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)');
  applyTheme(storedTheme() || (systemDark && systemDark.matches ? 'dark' : 'light'));

  if (systemDark && systemDark.addEventListener) {
    systemDark.addEventListener('change', function (e) {
      if (!storedTheme()) applyTheme(e.matches ? 'dark' : 'light');
    });
  }

  document.addEventListener('click', function (e) {
    var btn = e.target.closest && e.target.closest('#themeToggle');
    if (!btn) return;
    var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    applyTheme(next);
    saveTheme(next);
  });

  document.addEventListener('DOMContentLoaded', function () {
    applyTheme(root.getAttribute('data-theme') || 'light');

    /* ---------- Ticker: duplicate items so the loop has no gap ----------- */
    var track = document.querySelector('#daglianTrack');
    if (track && track.children.length) {
      var clone = track.cloneNode(true);
      while (clone.firstElementChild) track.appendChild(clone.firstElementChild);
    }

    /* ---------- Sliders --------------------------------------------------- */
    document.querySelectorAll('.slider-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var name = btn.getAttribute('data-slider');
        var wrap = document.querySelector('[data-slider-track="' + name + '"]');
        if (!wrap) return;
        var slides = Array.prototype.slice.call(wrap.querySelectorAll('.slide'));
        if (slides.length < 2) return;
        var current = slides.findIndex(function (s) { return s.classList.contains('is-active'); });
        if (current < 0) current = 0;
        var step = btn.getAttribute('data-dir') === 'prev' ? -1 : 1;
        var next = (current + step + slides.length) % slides.length;
        slides[current].classList.remove('is-active');
        slides[next].classList.add('is-active');
      });
    });

    /* ---------- Sidebar tabs --------------------------------------------- */
    var tabs = document.querySelector('#sideTabs');
    if (tabs) {
      tabs.querySelectorAll('.tabs__btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
          tabs.querySelectorAll('.tabs__btn').forEach(function (b) {
            var on = b === btn;
            b.classList.toggle('is-active', on);
            b.setAttribute('aria-selected', String(on));
          });
          tabs.querySelectorAll('.tabs__panel').forEach(function (panel) {
            var on = panel.id === btn.getAttribute('aria-controls');
            panel.classList.toggle('is-active', on);
            panel.hidden = !on;
          });
        });
      });
    }

    /* ---------- Nav + search --------------------------------------------- */
    var navToggle = document.querySelector('#navToggle');
    var mainMenu = document.querySelector('#mainMenu');
    if (navToggle && mainMenu) {
      navToggle.addEventListener('click', function () {
        var open = mainMenu.classList.toggle('open');
        navToggle.setAttribute('aria-expanded', String(open));
      });
    }

    var searchToggle = document.querySelector('#searchToggle');
    var searchPanel = document.querySelector('#searchPanel');
    if (searchToggle && searchPanel) {
      searchToggle.addEventListener('click', function () {
        var open = searchPanel.hidden;
        searchPanel.hidden = !open;
        searchToggle.setAttribute('aria-expanded', String(open));
        if (open) {
          var field = searchPanel.querySelector('input[type="search"]');
          if (field) field.focus();
        }
      });
    }

    /* ---------- Back to top ---------------------------------------------- */
    var backToTop = document.querySelector('#backToTop');
    if (backToTop) {
      window.addEventListener('scroll', function () {
        backToTop.classList.toggle('visible', window.scrollY > 500);
      }, { passive: true });
      backToTop.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }
  });
})();
