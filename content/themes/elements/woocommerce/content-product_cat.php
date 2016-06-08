<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce_loop;

// Store loop count we're currently on.
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid.
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Increase loop count.
$woocommerce_loop['loop']++;
?>
<li <?php wc_product_cat_class( '', $category ); ?>>
	<?php
  // Category title
  echo '<div class="list-item-40">';
  	/**
  	 * woocommerce_shop_loop_subcategory_title hook.
  	 *
  	 * @hooked woocommerce_template_loop_category_title - 10
  	 */
  	do_action( 'woocommerce_shop_loop_subcategory_title', $category );

  	/**
  	 * woocommerce_after_subcategory_title hook.
  	 */
  	do_action( 'woocommerce_after_subcategory_title', $category );
  echo '</div>';

  // Category arena
  $arena_name = get_field('arena_name', $category);
  $arena_location_city = get_field('arena_location_city', $category);
  $arena_location_country = get_field('arena_location_country', $category);

  echo '<div class="list-item-20">';
    echo '<p>' . $arena_name . '</p>';
  echo '</div>';

  echo '<div class="list-item-20">';
    echo '<p>' . $arena_location_city . '</p>';
  echo '</div>';

  // Category button
  echo '<div class="list-item-20">';
    $category_link = get_category_link($category->term_id);
    echo '<a class="button" href="' . $category_link . '">view details</a>';
  echo '</div>';
  ?>
</li>
