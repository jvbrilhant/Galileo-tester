/**
 * [NOME DO PROJETO] — Home demo behaviour.
 * Progressive enhancement only: the page is fully usable without this file.
 * Scope (closed): mobile menu, hero animation pause, demo-mode links, footer year.
 * Plain IIFE (works from file://, where ES modules are blocked) with no globals.
 */
(function () {
  'use strict';

  /**
   * 1. Mobile menu disclosure.
   * Toggles aria-expanded on the button (CSS shows/hides the panel from it),
   * closes on Escape and returns focus to the button.
   */
  function initMenu() {
    var toggle = document.querySelector('.gc-nav__toggle');
    var panel = toggle && document.getElementById(toggle.getAttribute('aria-controls'));
    if (!toggle || !panel) return;

    function setOpen(open) {
      toggle.setAttribute('aria-expanded', String(open));
    }

    toggle.addEventListener('click', function () {
      setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
        setOpen(false);
        toggle.focus();
      }
    });

    // Reset the state when the layout switches to the desktop menu.
    var desktop = window.matchMedia('(min-width: 1024px)');
    var onChange = function (e) { if (e.matches) setOpen(false); };
    if (desktop.addEventListener) desktop.addEventListener('change', onChange);
  }

  /**
   * 2. Pause hero animations when they cannot be seen.
   * Adds .is-paused to the hero when it is out of the viewport
   * (IntersectionObserver) or when the tab is hidden (visibilitychange).
   */
  function initHeroPause() {
    var hero = document.querySelector('.gc-hero');
    if (!hero) return;

    var inView = true;

    function update() {
      hero.classList.toggle('is-paused', !inView || document.hidden);
    }

    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        inView = entries[entries.length - 1].isIntersecting; // the latest record wins
        update();
      }).observe(hero);
    }

    document.addEventListener('visibilitychange', update);
  }

  /**
   * 3. Demo mode.
   * While <html data-demo="true">, clicks on internal links ("/…") are
   * intercepted and a polite status message is shown for 3 seconds.
   * Removing the attribute disables this without any other change.
   */
  function initDemoMode() {
    if (document.documentElement.getAttribute('data-demo') !== 'true') return;

    var notice = document.querySelector('.gc-demo-notice');
    if (!notice) return;

    var MESSAGE = 'This page will be available in the full site.';
    var hideTimer;
    var clearTimer;

    function show() {
      clearTimeout(hideTimer);
      clearTimeout(clearTimer);
      // Empty first so screen readers re-announce repeated messages.
      notice.textContent = '';
      window.setTimeout(function () {
        notice.textContent = MESSAGE;
        notice.classList.add('is-visible');
      }, 50);
      hideTimer = window.setTimeout(function () {
        notice.classList.remove('is-visible');
        clearTimer = window.setTimeout(function () { notice.textContent = ''; }, 300);
      }, 3050);
    }

    document.addEventListener('click', function (event) {
      var link = event.target.closest && event.target.closest('a[href]');
      if (!link) return;
      var href = link.getAttribute('href');
      // Internal site paths only: "/…" but not protocol-relative "//…".
      if (href.charAt(0) === '/' && href.charAt(1) !== '/') {
        event.preventDefault();
        show();
      }
    });
  }

  /**
   * 4. Footer year. The static year in the HTML is the no-JS fallback.
   */
  function initYear() {
    var year = String(new Date().getFullYear());
    document.querySelectorAll('[data-gc-year]').forEach(function (el) {
      el.textContent = year;
    });
  }

  initMenu();
  initHeroPause();
  initDemoMode();
  initYear();
})();
