<?php
function list_variations() {
  global $product;
  $parentId = $product->id;
  $variations = $product->get_available_variations();

  foreach($variations as $var){
    $varId = $var['variation_id'];
    $name = $var['attributes']['attribute_pa_seating'];
    $price = $var['display_regular_price'];
    ?>
    <tr>
      <form method="post" data-product_id="<?php echo $varId; ?>">
        <td class="attributes"><?php echo $name; ?></td>
        <td class="price"><?php echo $price; ?></td>
        <td class="quantity">
          <input type="number" step="1" min="" max="" name="quantity" value="1" title="Qty" class="input-text qty text" size="4">
        </td>
        <td class="add-to-cart">
          <select hidden name="attribute_pa_seating" data-attribute_name="attribute_pa_seating">
            <option value="<?php echo $name; ?>" selected="selected"></option>
          </select>

          <button type="submit" class="single_add_to_cart_button button alt">Buy now</button>
          <input type="hidden" name="add-to-cart" value="<?php echo $parentId; ?>">
          <input type="hidden" name="product_id" value="<?php echo $parentId; ?>">
          <input type="hidden" name="variation_id" value="<?php echo $varId; ?>">
        </td>
      </form>
    </tr>
    <?php
  }
}
?>