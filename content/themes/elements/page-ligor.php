<?php
get_header();

if( have_posts() ):
  while( have_posts() ): the_post();

    /**
     * woocommerce_before_main_content hook.
     *
     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
     * @hooked woocommerce_breadcrumb - 20
     */
    do_action( 'woocommerce_before_main_content' );

    /*
     * Get children by custom field
     *
     * Reference: https://www.advancedcustomfields.com/resources/get-values-from-a-taxonomy-term/
     */
    $regions = get_terms('region', array('hide_empty' => false));

    if( $regions ):

      page_content_start();

        echo '<h3>Alla ligor</h3>';

        echo '<ul class="list list-cloud">';

          foreach($regions as $region):
            $children = get_field('region_childLeagues', $region);

            if( $children ):
              echo '<div>';
                echo '<h4>' . $region->name . '</h4>';

                foreach($children as $child):
                  $term = get_term_by('id', $child, 'league');

                  echo '<li><a href="' . get_term_link( $term ) . '">' . $term->name . '</a></li>';
                endforeach;
              echo '</div>';
            endif;
          endforeach;

        echo '</ul>';

      page_content_end();

    endif;

      page_sidebar();

  endwhile;
endif;

get_footer();
?>