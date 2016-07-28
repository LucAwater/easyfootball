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

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
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

    <?php page_sidebar(); ?>

    <?php page_content_start(); ?>

      <?php
      /*
       * Info message for product
       *
       * Specific for each product
       */
      $infoMessage = get_field('product_notice');
      if( $infoMessage ){
        $type = "info";
        alert($type, $infoMessage);
      }
      ?>

      <?php
      /*
       * Put all variations in list
       *
       * Each variation has its own form
       */
      ?>
      <div class="variations">
        <ul class="list-col">
          <li class="list-col-head">
            <p class="list-item-40 attributes">Sektion</p>
            <p class="list-item-20 price">Pris</p>
            <p class="list-item-20 quantity">Antal</p>
            <p class="list-item-20 add-to-cart"></p>
          </li>

          <?php list_variations(); ?>
        </ul>
      </div>
    <?php page_content_end(); ?>
	</div><!-- .summary -->

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
