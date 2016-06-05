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
get_template_part( 'taxonomy', 'header' );
?>

<?php page_content_start(); ?>
  <?php
  /*
   * Get children by custom field
   *
   * Reference: https://www.advancedcustomfields.com/resources/get-values-from-a-taxonomy-term/
   */

  $children = get_field('region_childLeagues', $term);

  if( $children ):

    woocommerce_product_loop_start();

      foreach($children as $child){
        $child = get_term($child);
        $image = get_field('league_imageFeatured', $child);
        ?>

        <li>
          <figure>
            <?php if( $image ): ?>
              <img src="<?php echo $image['sizes']['medium_large']; ?>" width="<?php echo $images['sizes']['image-width']; ?>" height="<?php echo $image['sizes']['image-height']; ?>" />
            <?php else: ?>
              <img src="<?php echo get_template_directory_uri(); ?>/img/placeholder-league.png" />
            <?php endif; ?>
            <h4><?php echo $child->name; ?></h4>
          </figure>
        </li>

        <?php
      }

    woocommerce_product_loop_end();
  endif;
  ?>
<?php page_content_end(); ?>

<?php page_sidebar(); ?>

<?php get_footer(); ?>