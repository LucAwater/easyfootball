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
 * Team intro
 */
get_template_part( 'taxonomy', 'header' );
?>

<?php page_content_start(); ?>

  <?php
  // Get posts and order by custom field 'match_date'
  $matches = get_posts(array(
    'post_type'   => 'product',
    'numberposts' => -1,
    'meta_key'    => 'match_date',
    'orderby'     => 'meta_value',
    'order'       => 'asc',
    'tax_query'   => array(
      array(
        'taxonomy'          => 'team',
        'field'             => 'id',
        'terms'             => $term->term_id,
        'include_children'  => false
      )
    )
  ));

  if( $matches ):
    woocommerce_product_loop_start();
      woocommerce_product_subcategories();

      foreach( $matches as $post ):
        setup_postdata( $post );

        wc_get_template_part( 'content', 'product' );

        wp_reset_postdata();
      endforeach;

    woocommerce_product_loop_end();
  endif;
  ?>

<?php page_content_end(); ?>

<?php page_sidebar(); ?>

<?php get_footer(); ?>