/**
 * Kadence Child - Combined theme scripts (jQuery).
 */
(function ($, window) {
  'use strict';

  $(document).ready(function () {
    // --- Product category filter: WooCommerce SelectWoo (searchable) + redirect on change ---
    var $productCat = $('#filtered_product_cat');
    if ($productCat.length && typeof $.fn.selectWoo !== 'undefined') {
      $productCat.selectWoo({
        placeholder: $productCat.find('option:first').text(),
        allowClear: true,
        width: '100%'
      });
    }
    $productCat.on('change', function () {
      var url = $(this).val();
      if (url) {
        window.location.href = url;
      } else {
        var shopUrl = $(this).data('shop-url');
        if (shopUrl) {
          window.location.href = shopUrl;
        }
      }
    });

    // --- Product Categories / Testimonials Swiper ---
    if (typeof window.Swiper !== 'undefined') {
      $('.kadence_product_categories_view_carousel .kadence_product_categories_swiper, .kadence_testimonials_swiper').each(function () {
        var $this = $(this);
        var perRow = parseInt($this.data('per-row') || 6, 10);
        var auto = String($this.data('auto')) === '1';

        if (perRow < 1) perRow = 1;
        if (perRow > 6) perRow = 6;

        var isTestimonials = $this.hasClass('kadence_testimonials_swiper');

        var config = {
          slidesPerView: perRow,
          spaceBetween: 20,
          loop: isTestimonials ? true : false,
          autoplay: isTestimonials
            ? false
            : auto
              ? {
                  delay: 4000,
                  disableOnInteraction: false,
                }
              : false,
          breakpoints: {
            0: {
              slidesPerView: 1,
            },
            640: {
              slidesPerView: Math.min(2, perRow),
            },
            1024: {
              slidesPerView: perRow,
            },
          },
        };

        if (isTestimonials) {
          var $wrapper = $this.closest('.kadence_testimonials_wrapper');
          config.navigation = {
            nextEl: $wrapper.find('.kadence_testimonials_button_next')[0],
            prevEl: $wrapper.find('.kadence_testimonials_button_prev')[0],
          };
          config.allowTouchMove = false;
        }

        new window.Swiper(this, config);
      });
    }

    // --- Stats Counter (desktop only; requires CountUp) ---
    var CountUpCtor =
      window.countUp && typeof window.countUp.CountUp === 'function'
        ? window.countUp.CountUp
        : null;

    if (CountUpCtor) {
      var counters = [];

      function isElementInViewport(el) {
        if (!el || !el.getBoundingClientRect) {
          return false;
        }
        var rect = el.getBoundingClientRect();
        var viewHeight = window.innerHeight || document.documentElement.clientHeight || 0;
        var viewWidth = window.innerWidth || document.documentElement.clientWidth || 0;
        return (
          rect.bottom >= 0 &&
          rect.right >= 0 &&
          rect.top <= viewHeight &&
          rect.left <= viewWidth
        );
      }

      $('.kadence_stats_counter').each(function () {
        var $this = $(this);
        var id = $this.attr('data-id');
        var value = parseFloat($this.attr('data-value') || 0);
        var duration = parseFloat($this.attr('data-duration') || 2.5);

        if (!id || isNaN(value)) {
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
        maybeStart();
      });
    }
  });
})(jQuery, window);
