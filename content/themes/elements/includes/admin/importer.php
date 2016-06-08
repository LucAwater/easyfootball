<?php
// Add the options page with ACF
if( function_exists('acf_add_options_page') ) {

  acf_add_options_page( array(
    'page_title' => 'Import data',
    'menu_slug' => 'import-football-data',
    'parent_slug' => 'edit.php?post_type=product'
  ) );

}
?>