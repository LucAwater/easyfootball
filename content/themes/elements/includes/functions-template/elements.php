<?php
/*
 * This function gets all the custom fields from the backend
 *
 * Important: wpe-acf.json must be imported in order to use this function.
 */
function get_elements(){

  /*
   * Start the ACF page elements loop
   */
  if( have_rows('elements') ):
    while( have_rows('elements') ): the_row();

      if( get_row_layout() === 'text' ):
        get_template_part( 'elements/text' );
      elseif( get_row_layout() === 'image' ):
        get_template_part( 'elements/image' );
      endif;

    endwhile;
  endif;

}
?>
