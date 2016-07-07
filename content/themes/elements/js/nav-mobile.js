(function($) {

  function openPanel() {
    var triggerOpen = $('#navMobile-open');
    var triggerClose = $('#navMobile-close');
    var navMobile = $('#navMobile');

    triggerOpen.click( function() {
      navMobile.addClass('is-active');
    });

    triggerClose.click( function() {
      navMobile.removeClass('is-active');
    });
  }

  function subMenu() {
    var items = $('#navMobile-menu ul > li');
    var itemLinks = $('#navMobile-menu ul > li > a');

    itemLinks.click( function(e) {
      e.preventDefault();
    });

    items.click( function() {
      $(this).siblings().removeClass('is-active');
      $(this).toggleClass('is-active');
    });
  }

  openPanel();
  subMenu();

}( jQuery ));
