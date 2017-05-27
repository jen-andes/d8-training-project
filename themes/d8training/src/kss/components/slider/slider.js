(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.slider = {
    attach: function (context) {

      /*-----------------------------
        SLIDER ACTIVE
      ------------------------------*/
      var mySlider = $('.pogoSlider').pogoSlider({
        pauseOnHover: false
      }).data('plugin_pogoSlider');
      
    }
  };
})(Drupal, jQuery);
