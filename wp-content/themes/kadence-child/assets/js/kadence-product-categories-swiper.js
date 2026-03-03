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

    $('.kadence_product_categories_view_carousel .kadence_product_categories_swiper').each(function () {
      var $this = $(this);
      var perRow = parseInt($this.data('per-row') || 6, 10);
      var auto = String($this.data('auto')) === '1';

      // Clamp perRow
      if (perRow < 1) perRow = 1;
      if (perRow > 6) perRow = 6;

      /* eslint-disable no-new */
      new window.Swiper(this, {
        slidesPerView: perRow,
        spaceBetween: 20,
        loop: false,
        autoplay: auto
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
      });
      /* eslint-enable no-new */
    });
  });
})(jQuery, window);

