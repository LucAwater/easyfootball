<?php
/*
 * Taxonomy header with general information
 *
 * Called in taxonomy templates for regions, leagues and teams
 */

$term =	$wp_query->queried_object;
?>

<section class="taxonomy-header">
  <h2><?php echo $term->name; ?></h2>

  <figure>
    <!-- League featured image -->
  </figure>

  <div><?php echo wpautop($term->description); ?></div>
</section>