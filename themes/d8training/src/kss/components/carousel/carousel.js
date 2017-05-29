(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.carousel = {
    attach: function (context) {

      /*---------------------------
        TESTMONIAL SLIDER
      -----------------------------*/
      $('.carousel-slider').owlCarousel({
        merge: true,
        video: true,
        items: 1,
        smartSpeed: 1000,
        loop: true,
        nav: true,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        autoplay: false,
        autoplayTimeout: 2000,
        margin: 15,
        responsiveClass: true,
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 1
          },
          1000: {
            items: 1
          }
        }
      });

    }
  };
})(Drupal, jQuery);
