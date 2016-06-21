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
?>
<!-- Page header -->
<section class="page-header page-header-taxonomy">
  <div class="section-body">
    <div>
      <h1><?php echo $term->name; ?></h1>
      <?php echo wpautop($term->description); ?>
    </div>

    <?php if( $image ): ?>
      <figure>
        <img src="<?php echo $image_url; ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" />
      </figure>
    <?php endif; ?>
  </div>
</section>
