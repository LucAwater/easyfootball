<?php
function section_header($class_header, $title) {
  // Header
  if( $title ){
    echo '<div class="' . $class_header . '">';

      // Header title
      echo ( $title ) ? '<h2>' . $title . '</h2>' : '';

    echo '</div>';
  }
}
?>
