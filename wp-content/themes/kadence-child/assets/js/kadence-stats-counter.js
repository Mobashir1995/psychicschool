/**
 * Kadence Child - Stats Counter behavior (based on MasterStudy).
 *
 * Requires UMD CountUp v2 (bundled in `vendors/countUp/countUp.min.js`)
 * and an `is_on_screen` helper (from WPBakery/MasterStudy or theme).
 */
(function ($, window) {
  'use strict';

  $(document).ready(function () {
    var counters = [];

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
        console.log('no id', id);
        console.log('no value', value);
        console.log('no CountUpCtor', CountUpCtor);
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

      $(window).on('scroll.kadenceStatsCounter resize.kadenceStatsCounter', function () {
        var $el = $('#' + id);
        if ($el.length && typeof $el.is_on_screen === 'function') {
          if ($el.is_on_screen() && !counters[id].started) {
            counters[id].counter.start();
            counters[id].started = true;
          }
        } else if ($el.length && !counters[id].started) {
          // Fallback: start immediately if helper is missing.
          counters[id].counter.start();
          counters[id].started = true;
        }
      });
    });
  });
})(jQuery, window);

