<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

?>

<?php
$cat = get_query_var( 'product_cat' );
$cat = get_term_by( 'slug', $cat, 'product_cat');
$parents = get_category_parents($cat);
if( is_product_category() ){
  $parents = explode("/", $parents);
  $parents_count = count($parents) - 1;
} else {
  $parents_count = 0;
}
?>

<?php if( $parents_count == 0 ): ?>
  <ul class="products list-col">
    <li class="list-col-head">
      <p class="list-item-40">Country</p>
      <p class="list-item-20"></p>
    </li>
<?php elseif( $parents_count == 1 ): ?>
  <ul class="products list-col">
    <li class="list-col-head">
      <p class="list-item-40">League</p>
      <p class="list-item-20"></p>
    </li>
<?php elseif( $parents_count == 2 ): ?>
  <ul class="products list-col">
    <li class="list-col-head">
      <p class="list-item-40">Team</p>
      <p class="list-item-20">Arena</p>
      <p class="list-item-20">City</p>
      <p class="list-item-20"></p>
    </li>
<?php elseif( $parents_count == 3 ): ?>
  <ul class="products list-col">
    <li class="list-col-head">
      <p class="list-item-40">Match</p>
      <p class="list-item-20">Date</p>
      <p class="list-item-20">Prices</p>
      <p class="list-item-20"></p>
    </li>
<?php else: ?>
  <ul class="products">
<?php endif; ?>
