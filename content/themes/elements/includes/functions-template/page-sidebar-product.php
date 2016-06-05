<?php
/*
 * Retrieving seating map from category through ACF relationship field
 *
 * Reference: https://www.advancedcustomfields.com/resources/get-values-from-a-taxonomy-term/
 */
$match_location = get_field('match_location');

if( $match_location ):
  // Get category term to retrieve custom fields of (sub)category
  $category = $match_location;
  $category_tax = $category->taxonomy;
  $category_id = $category->term_id;
  $category_term = $category_tax . '_' . $category_id;

  $map = get_field('seating_map', $category_term);
  $map_url = $map['sizes']['medium'];
  $map_width = $map['sizes']['medium-width'];
  $map_height = $map['sizes']['medium-height'];
  ?>
  <aside>
    <div>
      <figure class="product-seating">
        <img src="<?php echo $map_url; ?>" width="<?php echo $map_width; ?>" height="<?php echo $map_height; ?>" />
      </figure>
    </div>
  </aside>
<?php else: ?>
  <aside>
    <div>
      <figure class="product-seating">
        <p><?php _e('No seating map available'); ?></p>
      </figure>
    </div>
  </aside>
<?php endif; ?>