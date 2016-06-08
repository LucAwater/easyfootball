<?php
function alert($type, $message){
  ?>
  <div class="alert alert-<?php echo $type; ?>">
    <p><?php echo $message; ?></p>
    <img class="alert-icon" src="<?php echo get_template_directory_uri(); ?>/img/icon-<?php echo $type; ?>.svg" />
  </div>
  <?php
}
?>