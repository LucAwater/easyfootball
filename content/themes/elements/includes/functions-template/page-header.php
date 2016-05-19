<?php
function page_header() {
  if( is_front_page() ):
    include_once('page-header-home.php');
  endif;
}
?>