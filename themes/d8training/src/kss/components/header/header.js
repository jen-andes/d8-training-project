(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.header = {
    attach: function (context) {

      /*--------------------------
        STICKY MAINMENU
      ---------------------------*/
      $("#mainmenu-area").sticky({
        topSpacing: 0
      });
      
      /* -------------------------
        SCROLLSPY ACTIVE
      -------------------------- */
      /*$('body').scrollspy({
        target: '.bs-example-js-navbar-scrollspy',
        offset: 50
      });*/

      /*--------------------------------
          DROPDOWN MOBILE MENU
      ----------------------------------*/
      function doneResizing() {
          if (Modernizr.mq('screen and (max-width:991px)')) {
              $('.at-drop-down').on('click', function (e) {
                  e.preventDefault();
                  $(this).next($('.sub-menu')).slideToggle();
              });
          }
      }
      var id;
      $(window).resize(function () {
          clearTimeout(id);
          id = setTimeout(doneResizing, 0);
      });
      doneResizing();

    }
  };
})(Drupal, jQuery);
