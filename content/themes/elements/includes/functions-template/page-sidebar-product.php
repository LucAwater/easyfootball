<?php
/*
 * Retrieving seating map from category through ACF relationship field
 *
 * Reference: https://www.advancedcustomfields.com/resources/get-values-from-a-taxonomy-term/
 */
$match_location = get_field('match_location');
$match_location = get_term_by('name', $match_location, 'team');

if( $match_location ):
  // Get category term to retrieve custom fields of (sub)category
  $category = $match_location;
  $category_tax = $category->taxonomy;
  $category_id = $category->term_id;
  $category_term = $category_tax . '_' . $category_id;

  $map = get_field('seating_map', $category_term);
  $map_url = $map['sizes']['large'];
  $map_width = $map['sizes']['large-width'];
  $map_height = $map['sizes']['large-height'];
  ?>
  <aside>
    <div>
      <figure class="product-seating">
        <?php if($map): ?>
          <a class="zoom zoom-in"></a>
          <a class="zoom zoom-out"></a>

          <div id="mapSeating"><img src="<?php echo $map_url; ?>" width="<?php echo $map_width; ?>" height="<?php echo $map_height; ?>" /></div>
        <?php else:  ?>
          <p><?php _e('Ingen arenaskiss tillgänglig'); ?></p>
        <?php endif; ?>
      </figure>
    </div>
  </aside>
<?php else: ?>
  <aside>
    <div>
      <figure class="product-seating">
        <p><?php _e('Ingen arenaskiss tillgänglig'); ?></p>
      </figure>
    </div>
  </aside>
<?php endif; ?>