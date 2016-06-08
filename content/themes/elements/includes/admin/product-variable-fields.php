<?php

// Add Variation Settings
add_action( 'woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3 );

// Save Variation Settings
add_action( 'woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2 );

/**
 * Create new fields for variations
 *
*/
function variation_settings_fields( $loop, $variation_data, $variation ) {

  // Number Field
  woocommerce_wp_text_input(
    array(
      'id'          => '_selling_pattern[' . $variation->ID . ']',
      'label'       => __( 'Selling pattern', 'woocommerce' ),
      'desc_tip'    => 'true',
      'value'       => get_post_meta( $variation->ID, '_selling_pattern', true ),
      'custom_attributes' => array(
        'step' 	=> 'any',
        'min'	=> '1',
        'max' => '3'
      )
    )
  );

}

/**
 * Save new fields for variations
 *
*/
function save_variation_settings_fields( $post_id ) {

  // Number Field
  $number_field = $_POST['_selling_pattern'][ $post_id ];
  if( ! empty( $number_field ) ) {
    update_post_meta( $post_id, '_selling_pattern', esc_attr( $number_field ) );
  }

}