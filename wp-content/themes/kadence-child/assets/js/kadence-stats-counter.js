/**
 * Kadence Child - Stats Counter behavior (based on MasterStudy).
 *
 * Requires UMD CountUp v2 (bundled in `vendors/countUp/countUp.min.js`)
 */
(function ($, window) {
  'use strict';

  $(document).ready(function () {
    var counters = [];

    function isElementInViewport(el) {
      if (!el || !el.getBoundingClientRect) {
        return false;
      }
      var rect = el.getBoundingClientRect();
      var viewHeight = window.innerHeight || document.documentElement.clientHeight || 0;
      var viewWidth = window.innerWidth || document.documentElement.clientWidth || 0;

      // Partially visible is considered "on screen"
      return (
        rect.bottom >= 0 &&
        rect.right >= 0 &&
        rect.top <= viewHeight &&
        rect.left <= viewWidth
      );
    }

    // UMD v2 exposes `window.countUp.CountUp`
    var CountUpCtor =
      window.countUp && typeof window.countUp.CountUp === 'function'
        ? window.countUp.CountUp
        : null;

    $('.kadence_stats_counter').each(function () {
      var $this = $(this);
      var id = $this.attr('data-id');
      var value = parseFloat($this.attr('data-value') || 0);
      var duration = parseFloat($this.attr('data-duration') || 2.5);

      if (!id || isNaN(value) || !CountUpCtor) {
        return;
      }

      counters[id] = {
        started: false,
        counter: new CountUpCtor(id, value, {
          duration: duration,
          useEasing: true,
          useGrouping: true,
          separator: ''
        })
      };

      function maybeStart() {
        if (counters[id].started) {
          return;
        }
        var el = document.getElementById(id);
        if (el && isElementInViewport(el)) {
          counters[id].counter.start();
          counters[id].started = true;
        }
      }

      $(window).on('scroll.kadenceStatsCounter resize.kadenceStatsCounter', maybeStart);
      maybeStart(); // attempt immediately on load
    });
  });
})(jQuery, window);

