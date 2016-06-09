<?php
get_header();

$term =	$wp_query->queried_object;
$term_acf = $term->taxonomy . '_' . $term->term_id;
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

  $leagues = get_field('region_childLeagues', $term_acf);

  if( $leagues ):

    woocommerce_product_loop_start();

      foreach($leagues as $league){
        $league = get_term_by('id', $league, 'league');
        $league_region = get_field('league_parentRegion', $league);
        $image = get_field('league_imageFeatured', $league);
        $image_url = $image['sizes']['medium'];
        $image_width = $image['sizes']['medium-width'];
        $image_height = $image['sizes']['medium-height'];
        $league_link = get_term_link($league, 'league');
        ?>

        <li>
          <div class="card-container">
            <figure>
              <?php if( $image ): ?>
                <img src="<?php echo $image_url; ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" />
              <?php else: ?>
                <img src="<?php echo get_template_directory_uri(); ?>/img/placeholder-league.png" />
              <?php endif; ?>

              <h4 href="<?php echo $league_link; ?>"><?php echo $league->name; ?></h4>
            </figure>

            <div class="card-actions">
              <a class="button button-small button-fullwidth" href="<?php echo $league_link; ?>">view teams</a>
            </div>
          </div>
        </li>

        <?php
      }

    woocommerce_product_loop_end();
  endif;
  ?>
<?php page_content_end(); ?>

<?php page_sidebar(); ?>

<?php get_footer(); ?>