<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product;
?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">

    <div class="product-info">
  		<?php
  			/**
  			 * woocommerce_single_product_summary hook.
  			 *
  			 * @hooked woocommerce_template_single_title - 5
  			 * @hooked woocommerce_template_single_rating - 10
  			 * @hooked woocommerce_template_single_price - 10
  			 * @hooked woocommerce_template_single_excerpt - 20
  			 * @hooked woocommerce_template_single_add_to_cart - 30
  			 * @hooked woocommerce_template_single_meta - 40
  			 * @hooked woocommerce_template_single_sharing - 50
  			 */
  			do_action( 'woocommerce_single_product_summary' );

        // Get match data from custom fields of product
        $match_date = get_field('match_date', false, false);
        $match_date = new DateTime($match_date);
        $match_time = get_field('match_time');
        $match_location = get_field('match_location');

        // Get category term to retrieve custom fields of (sub)category
        $category = get_category($match_location->term_id);
        $category_tax = $category->taxonomy;
        $category_id = $category->term_id;
        $category_term = $category_tax . '_' . $category_id;

        // Get custom fields for arena name and location of (sub)category
        $arena_name = get_field('arena_name', $category_term);
        $arena_location_city = get_field('arena_location_city', $category_term);
        $arena_location_country = get_field('arena_location_country', $category_term);
        $arena_location = $arena_name . ', ' . $arena_location_city . ', ' . $arena_location_country;

        // Build match data list
        if( $match_date || $match_time || $match_location ):
          echo '<ul>';
            if( $match_date )
              echo '<li><p><span>Date: </span>' . $match_date->format('l, j F Y') . '</p></li>';

            if( $match_time )
              echo '<li><p><span>Date: </span>' . $match_time . '</p></li>';

            if( $arena_name && $arena_location_city && $arena_location_country )
              echo '<li><p><span>Location: </span>' . $arena_location . '</p></li>';
          echo '</ul>';
        endif;
        ?>
    </div>

    <?php
    /*
     * Retrieving seating map from category through ACF relationship field
     *
     * Reference: https://www.advancedcustomfields.com/resources/get-values-from-a-taxonomy-term/
     */
    $match_location = get_field('match_location');
    $category = get_category($match_location->term_id);
    $category_tax = $category->taxonomy;
    $category_id = $category->term_id;
    $category_term = $category_tax . '_' . $category_id;
    $map = get_field('seating_map', $category_term);

    echo
    '<figure>
      <img src="' . $map['sizes']['medium'] . '" width="' . $map['sizes']['medium-width'] . '" height="' . $map['sizes']['medium-height'] . '">
    </figure>';
    ?>

    <?php
    /*
     * Put all variations in list
     *
     * Each variation has its own form
     */
    ?>
    <div class="variations">
      <ul>
        <li>
          <p class="attributes">Seating</p>
          <p class="price">Price</p>
          <p class="quantity">Quantity</p>
          <p class="add-to-cart"></p>
        </li>

        <?php list_variations(); ?>
      </ul>
    </div>

	</div><!-- .summary -->

	<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
