<?php
function enqueue_theme_scripts() {
  // Unregister standard jQuery and reregister as google code.
  wp_deregister_script('jquery');
  wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js', null, false, true );
  wp_enqueue_script( 'jquery' );

  if( WP_DEBUG ):
    wp_enqueue_script( 'scroll', get_template_directory_uri() . '/js/scroll.js', 'jquery', false, true );
    wp_enqueue_script( 'expand', get_template_directory_uri() . '/js/expand.js', 'jquery', false, true );
    wp_enqueue_script( 'nav-mobile', get_template_directory_uri() . '/js/nav-mobile.js', 'jquery', false, true );
  else:
    wp_enqueue_script( 'app-min', get_template_directory_uri() . '/js/app-min.js', 'jquery', false, true );
  endif;
}

add_action('wp_enqueue_scripts', 'enqueue_theme_scripts');
?>
