(function($) {

  var trigger = $('.expand-trigger');

  trigger.on( 'click', function() {
    var parent = trigger.parents(".expand-container");

    parent.toggleClass('is-open');
  });

}( jQuery ));
