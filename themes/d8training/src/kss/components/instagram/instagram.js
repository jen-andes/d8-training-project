(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.instagram = {
    attach: function (context) {

      /*----------------------------
        INSTAGRAM FEED ACTIVE
      -----------------------------*/
      jQuery.fn.spectragram.accessData = {
        accessToken: '2136707.4dd19c1.d077b227b0474d80a5665236d2e90fcf',
        clientID: '4dd19c1f5c7745a2bca7b4b3524124d0'
      }

      $('.instagram-feed').spectragram('getUserFeed', {
        query: 'adrianengine',
        size: 'big',
        max: 5
      });
      
      $('.blog-instagram-feed-widget').spectragram('getUserFeed', {
        query: 'adrianengine',
        size: 'small',
        max: 6
      });
          
    }
  };
})(Drupal, jQuery);
