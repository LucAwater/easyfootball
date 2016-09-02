<?php
if( function_exists('acf_add_options_page') ) {

  acf_add_options_page(array(
    'page_title' 	=> 'Featured lists',
    'menu_title'	=> 'Featured lists',
    'menu_slug' 	=> 'featured-lists',
    'redirect'		=> false
  ));

  acf_add_options_page(array(
    'page_title' 	=> 'Footer',
    'menu_title'	=> 'Footer',
    'menu_slug' 	=> 'footer',
    'redirect'		=> false
  ));

  acf_add_options_page(array(
    'page_title' 	=> 'Min Price',
    'menu_title'	=> 'Min Price',
    'menu_slug' 	=> 'minPrice',
    'parent_slug' => 'edit.php?post_type=product',
    'redirect'		=> false
  ));

}
?>