<?php
// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
  unset($fields['billing']['billing_address_2']);
  unset($fields['billing']['billing_company']);
  unset($fields['order']['order_comments']);


  return $fields;
}

/**
 * Add an info notice instead. Let's add it after other notices with priority = 11
 *
 * Reference: https://github.com/woothemes/woocommerce/blob/master/templates/checkout/form-checkout.php
 */
add_action( 'woocommerce_before_checkout_form', 'skyverge_add_checkout_notice', 11 );

function skyverge_add_checkout_notice() {
  wc_print_notice( __( 'Tickets will be sent to the venue', 'woocommerce' ), 'notice' );
}
?>