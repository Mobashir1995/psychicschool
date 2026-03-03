/**
 * Kadence Child - Product Categories Swiper init.
 *
 * Expects Swiper 12 UMD bundle loaded globally as `Swiper`.
 */
(function ($, window) {
  'use strict';

  $(document).ready(function () {
    if (typeof window.Swiper === 'undefined') {
      return;
    }

    $('.kadence_product_categories_view_carousel .kadence_product_categories_swiper, .kadence_testimonials_swiper').each(function () {
      var $this = $(this);
      var perRow = parseInt($this.data('per-row') || 6, 10);
      var auto = String($this.data('auto')) === '1';

      // Clamp perRow
      if (perRow < 1) perRow = 1;
      if (perRow > 6) perRow = 6;

      /* eslint-disable no-new */
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
      /* eslint-enable no-new */
    });
  });
})(jQuery, window);

