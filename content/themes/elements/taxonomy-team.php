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

<?php
if ( have_posts() ) :
  woocommerce_product_loop_start();
    woocommerce_product_subcategories();

    while ( have_posts() ) : the_post();
      wc_get_template_part( 'content', 'product' );
    endwhile;
  woocommerce_product_loop_end();
endif;

get_footer();
?>