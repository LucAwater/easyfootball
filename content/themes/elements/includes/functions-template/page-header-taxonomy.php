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
?>

<section class="taxonomy-header">
  <h2><?php echo $term->name; ?></h2>

  <figure>
    <img src="<?php echo $image['sizes']['medium_large']; ?>" width="<?php echo $images['sizes']['image-width']; ?>" height="<?php echo $image['sizes']['image-height']; ?>" />
  </figure>

  <div><?php echo wpautop($term->description); ?></div>
</section>