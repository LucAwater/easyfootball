<?php
get_header();

$term =	$wp_query->queried_object;

/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action( 'woocommerce_before_main_content' );

/**
 * Region intro
 */
echo '<h2>' . $term->name . '</h2>';

/*
 * Get children by custom field
 *
 * Reference: https://www.advancedcustomfields.com/resources/get-values-from-a-taxonomy-term/
 */

$children = get_field('region_children', $term);

if( $children ):

  woocommerce_product_loop_start();

    foreach($children as $child){
      $child = get_term($child);
      ?>

      <li>
        <div class="list-item-80">
          <p><?php echo $child->name; ?></p>
        </div>

        <div class="list-item-20">
          <a class="button" href="<?php echo get_term_link($child); ?>">view teams</a>
        </div>
      </li>

      <?php
    }

  woocommerce_product_loop_end();
endif;

get_footer();
?>