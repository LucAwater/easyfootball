<?php
function page_sidebar() {
  if( is_front_page() ):
    // no sidebar
  elseif( is_tax('league') ):
    include_once('page-sidebar-league.php');
  elseif( is_tax() ):
    include_once('page-sidebar-default.php');
  elseif( is_product() ):
    include_once('page-sidebar-product.php');
  elseif( is_cart() || is_checkout() ):
    // No sidebar
  else:
    //include_once('page-sidebar-default.php');
  endif;
}
?>
