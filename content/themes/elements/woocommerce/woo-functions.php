<?php
/**
 * Disable default Woocommerce styles
 */
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/**
 * woocommerce_before_main_content hook
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/**
 * woocommerce_before_shop_loop hook
 *
 * @hooked woocommerce_result_count - 20
 * @hooked woocommerce_catalog_ordering - 30
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

/**
 * woocommerce_before_subcategory_title hook.
 *
 * @hooked woocommerce_subcategory_thumbnail - 10
 */
remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );

/**
 * woocommerce_before_shop_loop_item hook.
 *
 * @hooked woocommerce_template_loop_product_link_open - 10
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );

/**
 * woocommerce_before_shop_loop_item_title hook.
 *
 * @hooked woocommerce_show_product_loop_sale_flash - 10
 * @hooked woocommerce_template_loop_product_thumbnail - 10
 */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

/**
 * woocommerce_after_shop_loop_item hook.
 *
 * @hooked woocommerce_template_loop_product_link_close - 5
 * @hooked woocommerce_template_loop_add_to_cart - 10
 */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

/**
 * woocommerce_before_single_product_summary hook.
 *
 * @hooked woocommerce_show_product_sale_flash - 10
 * @hooked woocommerce_show_product_images - 20
 */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

/**
* woocommerce_single_product_summary hook
*
* @hooked woocommerce_template_single_title - 5
* @hooked woocommerce_template_single_rating - 10
* @hooked woocommerce_template_single_price - 10
* @hooked woocommerce_template_single_excerpt - 20
* @hooked woocommerce_template_single_add_to_cart - 30
* @hooked woocommerce_template_single_meta - 40
* @hooked woocommerce_template_single_sharing - 50
*/
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

/**
 * After Single Products Summary Div
 *
 * @see woocommerce_output_product_data_tabs()
 * @see woocommerce_upsell_display()
 * @see woocommerce_output_related_products()
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
?>
