/**
 * Kadence Child - Stats Counter behavior (based on MasterStudy).
 *
 * Requires CountUpCountUp.min.js and an `is_on_screen` helper (from WPBakery/MasterStudy or theme).
 */
(function ($) {
  'use strict';

  $(document).ready(function () {
    var counters = [];

    $('.kadence_stats_counter').each(function () {
      var $this = $(this);
      var id = $this.attr('data-id');
      var value = parseFloat($this.attr('data-value') || 0);
      var duration = parseFloat($this.attr('data-duration') || 2.5);

      if (!id || isNaN(value) || typeof CountUpCountUp === 'undefined') {
        return;
      }

      counters[id] = {
        started: false,
        counter: new CountUpCountUp(id, 0, value, 0, duration, {
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
})(jQuery);

