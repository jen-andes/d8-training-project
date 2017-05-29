(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.story = {
    attach: function (context) {

      /*------------------------------
        ABOUT VIDEO POPUP
      --------------------------------*/
      $('.about-video-button,.blog-video-button').magnificPopup({
        disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 320,
        preloader: false
      });

    }
  };
})(Drupal, jQuery);
