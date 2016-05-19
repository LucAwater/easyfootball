<?php
function page_header() {
  if( is_front_page() ):
    include_once('page-header-home.php');
  elseif( is_tax() ):
    include_once('page-header-taxonomy.php');
  else:
    include_once('page-header-default.php');
  endif;
}
?>