<?php
function list_variations() {
  global $product;
  $parentId = $product->id;
  $variations = $product->get_available_variations();

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
          <p class="list-item-20 price"><?php echo $price; ?></p>

          <div class="list-item-10 quantity">
            <select name="quantity" class="input-text qty text">
              <?php
              for($i = 1; $i <= 10; $i++){
                echo '<option value="' . $i . '">' . $i . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="list-item-10 sellingPattern is-aligned-center">
            <?php
            $sellingPattern = get_post_meta( $varId, '_selling_pattern', true );

            if( $sellingPattern == 1 ){
              $tooltip_type = "warning";
              $tooltip_message = __("Only sold per single ticket");
            } elseif( $sellingPattern == 2){
              $tooltip_type = "warning";
              $tooltip_message = __("Only sold in pairs");
            }

            if( isset($tooltip_type) && isset($tooltip_message) && $sellingPattern != 3 ){
              echo '<span class="tooltip" data-tooltip="' . $tooltip_message . '"><img src="' . get_template_directory_uri() . '/img/icon-' . $tooltip_type .'-black.svg" /></span>';
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
        </form>
      </li>
      <?php
    }
  }
}
?>