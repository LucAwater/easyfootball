<?php
// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
  $fields['billing']['billing_first_name']['label'] = 'Förnamn';
  $fields['billing']['billing_last_name']['label'] = 'Efternamn';
  $fields['billing']['billing_address_1']['label'] = 'Adress';
  $fields['billing']['billing_address_1']['placeholder'] = '';
  $fields['billing']['billing_city']['label'] = 'Postort';
  $fields['billing']['billing_postcode']['label'] = 'Postnummer';
  $fields['billing']['billing_country']['label'] = 'Land';
  $fields['billing']['billing_state']['label'] = 'State';
  $fields['billing']['billing_email']['label'] = 'E-post';
  $fields['billing']['billing_phone']['label'] = 'Mobilnummer';

  unset($fields['billing']['billing_address_2']);
  unset($fields['billing']['billing_company']);
  unset($fields['order']['order_comments']);

  return $fields;
}

/**
 * Extra function to replace city and postcode fields
 * somehow, they get overwritten using just the woocommerce_checkout_fields filter
 */
function checkout_fields_override_extra( $translated_text, $text, $domain ) {
  switch ( $translated_text ) {
    case 'Postcode / ZIP' :
      $translated_text = __( 'Postnummer', 'woocommerce' );
      break;
    case 'Town / City' :
      $translated_text = __( 'Postort', 'woocommerce' );
      break;
  }
  return $translated_text;
}
add_filter( 'gettext', 'checkout_fields_override_extra', 20, 3 );

/**
 * Add an info notice instead. Let's add it after other notices with priority = 11
 *
 * Reference: https://github.com/woothemes/woocommerce/blob/master/templates/checkout/form-checkout.php
 */
// add_action( 'woocommerce_before_checkout_form', 'skyverge_add_checkout_notice', 11 );
//
// function skyverge_add_checkout_notice() {
//   wc_print_notice( __( 'Tickets will be sent to the venue', 'woocommerce' ), 'notice' );
// }
?>