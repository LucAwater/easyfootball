<?php
global $product, $post;
?>

<section class="page-header page-header-default">
  <div class="section-body">
    <h1 itemprop="name"><?php the_title(); ?></h1>
    <div class="product-description excerpt"><?php echo wpautop( get_the_excerpt() ); ?></div>

    <?php
    // Get match data from custom fields of product
    $match_date = get_field('match_date', false, false);
    $match_date = DateTime::createFromFormat('Y-m-j', $match_date);
    $match_time = get_field('match_time');
    $match_location = get_field('match_location');
    $match_location = get_term_by('name', $match_location, 'team');

    // Get category term to retrieve custom fields of (sub)category
    if( $match_location ){
      $category = $match_location;
      $category_tax = $category->taxonomy;
      $category_id = $category->term_id;
      $category_term = $category_tax . '_' . $category_id;

      // Get custom fields for arena name and location of (sub)category
      $arena_name = get_field('arena_name', $category_term);
      $arena_location_city = get_field('arena_location_city', $category_term);
      $arena_location_country = get_field('arena_location_country', $category_term);
      $arena_location = $arena_name . ', ' . $arena_location_city . ', ' . $arena_location_country;
    } else {
      $arena_name = '';
      $arena_location_city = '';
      $arena_location_country = '';
    }

    // Build match data list
    if( $match_date || $match_time || $match_location ):
      echo '<ul>';
        if( $match_date )
          echo '<li><p><strong>Date: </strong>' . $match_date->format('l, j F Y') . '</p></li>';

        if( $match_time )
          echo '<li><p><strong>Time: </strong>' . $match_time . '</p></li>';

        if( $arena_name && $arena_location_city && $arena_location_country )
          echo '<li><p><strong>Location: </strong>' . $arena_location . '</p></li>';
      echo '</ul>';
    endif;
    ?>
  </div>

  <?php
  // Get team logos
  $teams = wp_get_post_terms($post->ID, 'team');
  if( $teams ):
    echo '<div class="page-header-background">';
      if( $match_location ):
        // First get home team logo
        $team = $match_location;
        $team_acf = $team->taxonomy . '_' . $team->term_id;
        $team_logo = get_field('team_logo', $team_acf);

        if( isset($team_logo) ){
          $team_logo_url = $team_logo['sizes']['medium'];
          $team_logo_width = $team_logo['sizes']['medium-width'];
          $team_logo_height = $team_logo['sizes']['medium-height'];
        } else {
          $team_logo_url = get_template_directory_uri() . '/img/placeholder-team.svg';
          $team_logo_width = "";
          $team_logo_height = "";
        }
        echo '<img src="' . $team_logo_url . '" width="' . $team_logo_width . '" height="' . $team_logo_height . '" />';

        // Get away team logo
        foreach( $teams as $team ):
          if( $team->name !== $match_location->name ):
            $team_acf = $team->taxonomy . '_' . $team->term_id;
            $team_logo = get_field('team_logo', $team_acf);

            if( isset($team_logo) ){
              $team_logo_url = $team_logo['sizes']['medium'];
              $team_logo_width = $team_logo['sizes']['medium-width'];
              $team_logo_height = $team_logo['sizes']['medium-height'];
            } else {
              $team_logo_url = get_template_directory_uri() . '/img/placeholder-team.svg';
              $team_logo_width = "";
              $team_logo_height = "";
            }
            echo '<img src="' . $team_logo_url . '" width="' . $team_logo_width . '" height="' . $team_logo_height . '" />';
          endif;
        endforeach;
      endif;
    echo '</div>';
  endif;
  ?>
</section>
