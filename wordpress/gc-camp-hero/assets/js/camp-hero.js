/**
 * Camp Hero: pauses the hero animations while the hero is off-screen or the tab is hidden,
 * and opens a place's description above its label when there is no room below it.
 * Progressive enhancement only; the hero works without this file.
 */
(function () {
  'use strict';

  function init(hero) {
    if (hero.dataset.gcInit) return;
    hero.dataset.gcInit = '1';
    var inView = true;
    function update() { hero.classList.toggle('is-paused', !inView || document.hidden); }
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) { inView = entries[0].isIntersecting; update(); }).observe(hero);
    }
    document.addEventListener('visibilitychange', update);

    // Descriptions open below the label; flip them up if they would be cut off by the hero's bottom edge.
    function place(e) {
      var el = e.target.closest && e.target.closest('.gc-place');
      var desc = el && el.querySelector('.gc-place__desc');
      if (!desc || getComputedStyle(desc).position !== 'absolute') return;
      var label = desc.parentNode.getBoundingClientRect();
      el.classList.toggle('gc-place--up', label.bottom + desc.offsetHeight + 14 > hero.getBoundingClientRect().bottom);
    }
    hero.addEventListener('pointerover', place);
    hero.addEventListener('focusin', place);
  }

  function scan() { document.querySelectorAll('.gc-hero').forEach(init); }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', scan); else scan();

  // Elementor editor/preview renders widgets after load.
  window.addEventListener('elementor/frontend/init', function () {
    if (window.elementorFrontend && window.elementorFrontend.hooks) {
      window.elementorFrontend.hooks.addAction('frontend/element_ready/gc_camp_hero.default', function ($el) {
        var hero = ($el && $el[0] ? $el[0] : $el).querySelector('.gc-hero');
        if (hero) init(hero);
      });
    }
  });
})();
