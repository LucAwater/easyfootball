<?php
function page_header() {
  if( is_front_page() ):
    include_once('page-header-home.php');
  elseif( is_tax() ):
    include_once('page-header-taxonomy.php');
  elseif( is_product() ):
    include_once('page-header-product.php');
  elseif( is_search() ):
    include_once('page-header-search.php');
  elseif( is_page('lag') || is_page('ligor') || is_page('landskampaner') ):
    include_once('page-header-default.php');
  else:
    //include_once('page-header-default.php');
  endif;
}
?>
