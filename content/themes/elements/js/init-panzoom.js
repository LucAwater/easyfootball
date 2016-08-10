/*
 * Panzoom plugin
 * https://github.com/timmywil/jquery.panzoom/blob/master/README.md#loading-panzoom
 */

(function($) {

  var el = $('#mapSeating');
  var zoomIn = $('.zoom-in');
  var zoomOut = $('.zoom-out');

  el.panzoom({
    minScale: 1,
    contain: 'invert',
    $zoomRange: $("input[type='range']"),
    $zoomIn: $('a.zoom-in')
  });

  zoomIn.click( function(e) {
    e.preventDefault();
    el.panzoom("zoom");
  });

  zoomOut.click( function(e) {
    e.preventDefault();
    el.panzoom("zoom", true);
  });

}( jQuery ));
