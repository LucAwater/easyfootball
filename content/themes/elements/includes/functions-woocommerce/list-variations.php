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
      $name = $var['attributes']['attribute_seating'];
      $name = preg_replace('/-/', ' ', $name);
      $price = $var['display_regular_price'];
      ?>
      <li>
        <form method="post" data-product_id="<?php echo $varId; ?>">
          <p class="list-item-50 attributes"><?php echo $name; ?></p>
          <p class="list-item-10 price"><?php echo $price; ?></p>

          <div class="list-item-20 quantity">
            <input type="number" step="1" min="" max="" name="quantity" value="1" title="Qty" class="input-text qty text" size="4">
          </div>

          <div class="list-item-20 add-to-cart">
            <select hidden name="attribute_seating" data-attribute_name="attribute_seating">
              <option value="<?php echo $name; ?>" selected="selected"></option>
            </select>

            <button type="submit" class="single_add_to_cart_button button alt">Buy now</button>
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