(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.menu = {
    attach: function (context) {

      /*---------------------------
        MENU LIST MIXITUP FILTERING
      ----------------------------*/
      $('.food-menu-list').mixItUp();
      
    }
  };
})(Drupal, jQuery);
