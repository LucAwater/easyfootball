<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 === ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 === $woocommerce_loop['columns'] ) {
	$classes[] = 'first';
}
if ( 0 === $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) {
	$classes[] = 'last';
}
?>
<li <?php post_class( $classes ); ?>>

	<?php
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );

	/**
	 * woocommerce_before_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item_title' );

  $match_date = get_field('match_date', false, false);
  $match_date = new DateTime($match_date);
  $match_time = get_field('match_time');
  $match_location = get_field('match_location');
  $match_location = get_term_by('name', $match_location, 'team');

  // Get category term to retrieve custom fields of (sub)category
  if( $match_location ){
    $category = $match_location;
    $category_tax = $category->taxonomy;
    $category_id = $category->term_id;
    $category_term = $category_tax . '_' . $category_id;

    // Get custom fields for arena name and location of (sub)category
    $arena_name = get_field('arena_name', $category_term);
    $arena_location_city = get_field('arena_location_city', $category_term);
    $arena_location_country = get_field('arena_location_country', $category_term);
    $arena_location = $arena_name . ', ' . $arena_location_city . ', ' . $arena_location_country;
  } else {
    $arena_name = '';
    $arena_location_city = '';
    $arena_location_country = '';
  }

  // Match date
  echo '<div class="list-item-20">';
    echo '<p>' . $match_date->format('j M Y') . '</p>';
    echo '<small>' . $match_date->format('D') . ' ' . $match_time . '</small>';
  echo '</div>';

  // Match title
  echo '<div class="list-item-40">';
  	echo '<p>' . get_the_title() . '</p>';
    echo '<small>' . $arena_location . '</small>';
  echo '</div>';

  // Match price range
  echo '<div class="list-item-20">';
  	/**
  	 * woocommerce_after_shop_loop_item_title hook.
  	 *
  	 * @hooked woocommerce_template_loop_rating - 5
  	 * @hooked woocommerce_template_loop_price - 10
  	 */
  	do_action( 'woocommerce_after_shop_loop_item_title' );
  echo '</div>';

  // 'View tickets' button
  echo '<div class="list-item-20">';
  	/**
  	 * woocommerce_after_shop_loop_item hook.
  	 *
  	 * @hooked woocommerce_template_loop_product_link_close - 5
  	 * @hooked woocommerce_template_loop_add_to_cart - 10
  	 */
  	do_action( 'woocommerce_after_shop_loop_item' );
  echo '</div>';
	?>

</li>
