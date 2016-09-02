<?php
$minPrice_init = get_field('minPrice_init', 'option');
update_field('field_57c95871e57af', 0, 'option');
update_field('minPrice_init', 0, 'option');

if($minPrice_init){
  function products_add_min_price(){
    $minPrice_league = get_field('minPrice_league', 'option');
    $term = get_term_by( 'id', $minPrice_league, 'league' );

    $matches = get_posts(array(
      'post_type'   => 'product',
      'numberposts' => -1,
      'tax_query'   => array(
        array(
          'taxonomy'          => 'league',
          'field'             => 'slug',
          'terms'             => $term->slug,
          'include_children'  => false
        )
      )
    ));
    if( $matches ){
      $maxPosts = count($matches);
      for( $i = 0; $i < $maxPosts; $i++){
        $product = new WC_Product_Variable( $matches[$i]->ID );

        // Get product variations
        $variations = $product->get_available_variations();

        // Get variations with stock
        $whitelistItems = array();
        foreach($variations as $var){
          if( $var['is_in_stock'] != 0 ){
            $whitelistItems[] = $var['variation_id'];
          }
        }

        // Get prices of all variations
        $prices = $product->get_variation_prices( true );

        // Get regular prices in one array
        $prices = array_filter($prices['regular_price']);

        // Filter out the ones that do not have stock
        $prices = array_intersect_key($prices, array_flip($whitelistItems));

        // Get lowest value from array
        if( count($prices) > 1 ){
          $price = min($prices);
        } else {
          $price = current($prices);
        }
        if( empty($prices) ){
          $price = "På förfrågan";
        }

        update_post_meta($matches[$i]->ID, 'min_price', $price);
      }
    }
  }
  add_action( 'init', 'products_add_min_price', 100 );
}

function products_add_min_price_individual( $post_id ) {
  print_r('stock changed');
  // If this is just a revision, don't send the email.
  if ( wp_is_post_revision( $post_id ) )
    return;

  $product = new WC_Product_Variable( $post_id );

  // Get product variations
  $variations = $product->get_available_variations();

  // Get variations with stock
  $whitelistItems = array();
  foreach($variations as $var){
    if( $var['is_in_stock'] != 0 ){
      $whitelistItems[] = $var['variation_id'];
    }
  }

  // Get prices of all variations
  $prices = $product->get_variation_prices( true );

  // Get regular prices in one array
  $prices = array_filter($prices['regular_price']);

  // Filter out the ones that do not have stock
  $prices = array_intersect_key($prices, array_flip($whitelistItems));

  // Get lowest value from array
  if( count($prices) > 1 ){
    $price = min($prices);
  } else {
    $price = current($prices);
  }
  if( empty($prices) ){
    $price = "På förfrågan";
  }

  update_post_meta($post_id, 'min_price', $price);
}
add_action( 'save_post', 'products_add_min_price_individual' );

function products_add_min_price_stock($order) {
  $items = $order->get_items();
  if( $items ){
    foreach( $items as $item ){
      $product = new WC_Product_Variable( $item['product_id'] );

      // Get product variations
      $variations = $product->get_available_variations();

      // Get variations with stock
      $whitelistItems = array();
      foreach($variations as $var){
        if( $var['is_in_stock'] != 0 ){
          $whitelistItems[] = $var['variation_id'];
        }
      }

      // Get prices of all variations
      $prices = $product->get_variation_prices( true );

      // Get regular prices in one array
      $prices = array_filter($prices['regular_price']);

      // Filter out the ones that do not have stock
      $prices = array_intersect_key($prices, array_flip($whitelistItems));

      // Get lowest value from array
      if( count($prices) > 1 ){
        $price = min($prices);
      } else {
        $price = current($prices);
      }
      if( empty($prices) ){
        $price = "På förfrågan";
      }

      update_post_meta($item['product_id'], 'min_price', $price);
    }
  }
}
add_action( 'woocommerce_reduce_order_stock', 'products_add_min_price_stock', 10, 1 );

/**
* Register custom RSS template.
*/
function rss_template_matches() {
  add_feed( 'matches', 'rss_matches' );
  add_feed( 'matches-bundesliga', 'rss_matches_bundesliga' );
  add_feed( 'matches-championship', 'rss_matches_championship' );
  add_feed( 'matches-championsLeague', 'rss_matches_championsLeague' );
  add_feed( 'matches-laLiga', 'rss_matches_laLiga' );
  add_feed( 'matches-ligue1', 'rss_matches_ligue1' );
  add_feed( 'matches-premierLeague', 'rss_matches_premierLeague' );
  add_feed( 'matches-serieA', 'rss_matches_serieA' );
}
add_action( 'after_setup_theme', 'rss_template_matches' );

/**
* Custom RSS template callback.
*/
function rss_matches() {
  get_template_part( 'feeds/feed', 'matches' );
}

function rss_matches_bundesliga() { get_template_part( 'feeds/feed', 'bundesliga' ); }
function rss_matches_championship() { get_template_part( 'feeds/feed', 'championship' ); }
function rss_matches_championsLeague() { get_template_part( 'feeds/feed', 'championsLeague' ); }
function rss_matches_laLiga() { get_template_part( 'feeds/feed', 'laLiga' ); }
function rss_matches_ligue1() { get_template_part( 'feeds/feed', 'ligue1' ); }
function rss_matches_premierLeague() { get_template_part( 'feeds/feed', 'premierLeague' ); }
function rss_matches_serieA() { get_template_part( 'feeds/feed', 'serieA' ); }
?>