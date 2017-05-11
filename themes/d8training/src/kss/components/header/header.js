(function (Drupal, $) {
  'use strict';

  Drupal.behaviors.counter = {
    attach: function (context) {

      /*----------------------------
          OPEN SEARCH FORM
      ----------------------------*/
      var $searchForm = $('.search-form');
      var $searchFormTrigger = $('.search-form-trigger', context);
      var $formOverlay = $('.search-form-overlay');
      $searchFormTrigger.on('click', function (event) {
        event.preventDefault();
        toggleSearch();
      });

      function toggleSearch(type) {
        if (type === "close") {
          //close serach 
          $searchForm.removeClass('is-visible');
          $searchFormTrigger.removeClass('search-is-visible');
        } else {
          //toggle search visibility
          $searchForm.toggleClass('is-visible');
          $searchFormTrigger.toggleClass('search-is-visible');
          if ($searchForm.hasClass('is-visible')) $searchForm.find('input[type="search"]').focus();
          $searchForm.hasClass('is-visible') ? $formOverlay.addClass('is-visible') : $formOverlay.removeClass('is-visible');
        }
      }

    }
  };
})(Drupal, jQuery);
