<?php
function team_logos($match_location, $teams) {
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
}
?>