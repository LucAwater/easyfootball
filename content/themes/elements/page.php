<?php
get_header();

/*
 * Check if we are on a WooCommerce page
 * If so, echo content to enable the shortcode
 */
 if( have_posts() ):
   while( have_posts() ): the_post();

     if( is_really_woocommerce_page() ){
       the_content();
     }

   endwhile;
 endif;

/*
 * Start the ACF page elements loop
 *
 * Important: acf.json must be imported in order to use elements theme from this point forward.
 */
if( have_rows('page') ):
  // Every parallax element needs an id
  $i_par = 0;

  // Loop through ACF page elements
  while( have_rows('page') ): the_row();

    if( get_row_layout() == 'hero' ):
      get_template_part( 'elements/hero' );
    elseif( get_row_layout() == 'text' ):
      get_template_part( 'elements/text' );
    elseif( get_row_layout() == 'image' ):
      get_template_part( 'elements/image' );
    elseif( get_row_layout() == 'grid_primary' ):
      get_template_part( 'elements/gridPri' );
    elseif( get_row_layout() == 'grid_secondary' ):
      get_template_part( 'elements/gridSec' );
    elseif( get_row_layout() == 'slider' ):
      get_template_part( 'elements/slider' );
    elseif( get_row_layout() == 'parallax' ): $i_par++;
      get_template_part( 'elements/parallax' );
    endif;

  endwhile;
endif;

get_footer();
?>
