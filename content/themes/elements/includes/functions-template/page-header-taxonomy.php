<?php
/*
 * Taxonomy header with general information
 *
 * Called in taxonomy templates for regions, leagues and teams
 */
global $wp_query;
$term =	$wp_query->queried_object;

$term_acf = $term->taxonomy . '_' . $term->term_id;
$term_taxonomy = $term->taxonomy;
$image = get_field( $term_taxonomy . '_imageFeatured', $term_acf );
$image_url = $image['sizes']['medium'];
$image_width = $image['sizes']['medium-width'];
$image_height = $image['sizes']['medium-height'];
$description = wpautop($term->description);

$rand_int = rand(1, 2);

// background image
if( $image ){
  $bg_url = $image['sizes']['large'];
} else {
  $bg_url = get_template_directory_uri() . '/img/bg' . $rand_int . '.jpg';
}

// section class
if( $bg_url ){
  $section_class = "page-header page-header-taxonomy page-header-image";
} else {
  $section_class = "page-header page-header-taxonomy";
}
?>

<section class="<?php echo $section_class; ?>" style="background-image:url(<?php echo $bg_url; ?>)">
  <div class="section-body">
    <?php if( $image ): ?>
      <figure>
        <img src="<?php echo $image_url; ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" />
      </figure>
    <?php endif; ?>

    <div>
      <h1><?php echo $term->name; ?></h1>

      <?php if( strlen($description) > 500 ): ?>
        <div class="expand-container">
          <div class="expand-content">
            <?php echo $description; ?>
          </div>

          <div class="expand-trigger">
            <a></a>
          </div>
        </div>
      <?php else: ?>
        <?php echo $description; ?>
      <?php endif; ?>
    </div>
  </div>
</section>
