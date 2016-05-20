<?php
/*
 * Retrieving seating map from category through ACF relationship field
 *
 * Reference: https://www.advancedcustomfields.com/resources/get-values-from-a-taxonomy-term/
 */
$match_location = get_field('match_location');

if( $match_location ){
  // Get category term to retrieve custom fields of (sub)category
  $category = get_category($match_location->term_id);
  $category_tax = $category->taxonomy;
  $category_id = $category->term_id;
  $category_term = $category_tax . '_' . $category_id;
}
?>
<aside>
  <div>
    <figure class="product-seating">
      <?php $map = get_field('seating_map', $category_term); ?>

      <img src="<?php echo $map['sizes']['medium']; ?>" width="<?php echo $map['sizes']['medium-width']; ?>" height="<?php echo $map['sizes']['medium-height']; ?>" />
    </figure>
  </div>
</aside>