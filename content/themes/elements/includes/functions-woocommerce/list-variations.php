<?php
function list_variations() {
  global $product, $post;
  $parentId = $product->id;
  $variations = $product->get_available_variations();

  // Obtain a list of columns
  foreach ($variations as $key => $row) {
    $var_stock[$key]  = $row['is_in_stock'];
    $var_price[$key] = $row['display_price'];
  }

  // Sort the data with stock descending, price ascending
  array_multisort($var_stock, SORT_DESC, $var_price, SORT_ASC, $variations);

  // List variations
  if(! $variations ){
    echo '<li class="no-tickets"><p>No tickets available</p></li>';
  } else {
    foreach($variations as $var){
      $varId = $var['variation_id'];
      $var_product = new WC_Product_Variation( $varId );
      $name = $var['attributes']['attribute_seating'];
      $name = preg_replace('/-/', ' ', $name);
      $price = $var_product->regular_price;
      ?>
      <li>
        <form method="post" data-product_id="<?php echo $varId; ?>">
          <p class="list-item-40 attributes"><?php echo $name; ?></p>

          <?php if(! $var['is_in_stock']): ?>
            <small class="list-item-40 price"><?php _e('På förfrågan'); ?></small>

            <div class="list-item-20 add-to-cart">
              <?php
              $email_subject = "Biljettförfrågan för " . get_the_title() . ", " . $name . " section";
              ?>
              <a href="mailto:info@easyfootball.se?subject=<?php echo $email_subject; ?>" class="button button-sec"><?php _e('Kontakta mig'); ?></a>
            </div>
          <?php else: ?>
            <p class="list-item-20 price"><?php echo $price; ?></p>

            <div class="list-item-10 quantity">
              <select name="quantity" class="input-text qty text">
                <?php
                for($i = 1; $i <= 10; $i++){
                  if( $i === 1 ){
                    echo '<option selected="selected" value="' . $i . '">' . $i . '</option>';
                  } else {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                  }
                }
                ?>
              </select>
            </div>

            <div class="list-item-10 sellingPattern is-aligned-center">
              <?php
              $sellingPattern = get_post_meta( $varId, '_selling_pattern', true );

              if( $sellingPattern == 1 ){
                $tooltip_class = "tooltip tooltip-large";
                $tooltip_type = "warning";
                $tooltip_message = __("Dessa biljetter säljs endast som enskilda biljetter dvs det är ingen garanti att de är tillsammans");
              } elseif( $sellingPattern == 2){
                $tooltip_class = "tooltip";
                $tooltip_type = "warning";
                $tooltip_message = __("Dessa biljetter säljes endast i par");
              } else {
                $tooltip_class = "tooltip";
              }

              if( isset($tooltip_type) && isset($tooltip_message) && $sellingPattern < 3 ){
                echo '<span class="' . $tooltip_class . '" data-tooltip="' . $tooltip_message . '"><img src="' . get_template_directory_uri() . '/img/icon-' . $tooltip_type .'-black.svg" /></span>';
              }
              ?>
            </div>

            <div class="list-item-20 add-to-cart">
              <select hidden name="attribute_seating" data-attribute_name="attribute_seating">
                <option value="<?php echo $name; ?>" selected="selected"></option>
              </select>

              <button type="submit" class="single_add_to_cart_button button alt"><?php _e("Köp"); ?></button>
              <input type="hidden" name="add-to-cart" value="<?php echo $parentId; ?>">
              <input type="hidden" name="product_id" value="<?php echo $parentId; ?>">
              <input type="hidden" name="variation_id" value="<?php echo $varId; ?>">
            </div>
          <?php endif; ?>
        </form>
      </li>
      <?php
    }
  }
}
?>
