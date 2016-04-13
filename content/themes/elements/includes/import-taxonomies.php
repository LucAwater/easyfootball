<?php
function import_taxonomies() {
  // Insert countries as parent categories
  $countries = data_get_countries();

  foreach($countries as $country){
    wp_insert_term($country, 'product_cat');
  }

  // Get parent categories as objects
  $parents = get_terms('product_cat', array('hide_empty' => false));

  foreach($parents as $parent){
    // Check if not a subcategory
    if( $parent->parent == 0){
      // Get parent id
      $parent_ID = $parent->term_id;
      $parent_name = $parent->name;

      // Get list children(leagues)
      $children = data_get_leagues();

      // Loop over children
      foreach( $children as $child ){
        // Detect direct children
        if( $child['country'] == $parent_name ){
          // Insert children into DB
          wp_insert_term(
            $child['name'],
            'product_cat',
            array(
              'parent' => $parent_ID
            )
          );
        }
      }
    }
  }
}
add_action('admin_init', 'import_taxonomies');
?>