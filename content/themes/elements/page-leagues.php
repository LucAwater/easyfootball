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

      woocommerce_product_loop_start();

        foreach($regions as $region){
          ?>
          <li>
            <div class="list-item-80">
              <p><?php echo $region->name; ?></p>
            </div>

            <div class="list-item-20">
              <a class="button" href="<?php echo get_term_link($region); ?>">view leagues</a>
            </div>
          </li>

          <?php
        }

      woocommerce_product_loop_end();

    endif;

  endwhile;
endif;

get_footer();
?>