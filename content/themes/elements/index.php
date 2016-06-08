<?php
get_header();

if( have_posts() ):
  if( is_front_page() ):
    // do nothing
  else:
    get_template_part( 'archive' );
  endif;
endif;

get_footer();
?>