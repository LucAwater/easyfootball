<?php
function site_title() {
  if( is_tax() ){
    global $wp_query;
    $term =	$wp_query->queried_object;

    $title = 'Easyfootball - ' . $term->name;
  } else if( is_product() ){
    $title = 'Easyfootball - ' . get_the_title();
  } else if( is_checkout() ){
    $title = 'Easyfootball - checkout';
  } else if( is_cart() ){
    $title = 'Easyfootball - cart';
  } else {
    $title = 'Easyfootball';
  }

  echo '<title>' . $title . '</title>';
}
?>