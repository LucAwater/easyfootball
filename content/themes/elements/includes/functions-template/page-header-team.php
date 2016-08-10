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
$logo = get_field( 'team_logo', $term_acf );
$logo_url = $logo['sizes']['medium'];
$logo_width = $logo['sizes']['medium-width'];
$logo_height = $logo['sizes']['medium-height'];
$description = wpautop($term->description);

$rand_int = rand(1, 2);
$bg_url = get_template_directory_uri() . '/img/bg' . $rand_int . '.jpg';
if( $bg_url ){
  $section_class = "page-header page-header-taxonomy page-header-team page-header-image";
} else {
  $section_class = "page-header page-header-taxonomy page-header-team";
}
?>

<section class="<?php echo $section_class; ?>" style="background-image:url(<?php echo $bg_url; ?>)">
  <div class="section-body">
    <?php if( $logo ): ?>
      <figure>
        <img src="<?php echo $logo_url; ?>" width="<?php echo $logo_width; ?>" height="<?php echo $logo_height; ?>" />
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
