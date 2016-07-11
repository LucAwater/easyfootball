<?php
function import_regions(){
  $regions = data_get_regions();

  foreach($regions as $region){
    // Get league's name
    $region_name = $region['region'];

    // Check if term already exists
    if(! term_exists($region_name, 'region') ){
      // If not, insert term
      wp_insert_term($region_name, 'region');

      // After insert, check again and run update function
      if( term_exists($region_name, 'region') ){
        import_regions_updateFields($region_name);
      }
    } else {
      import_regions_updateFields($region, $region_name);
    }
  }

  import_teams();
}

function import_regions_updateFields($region, $region_name){
  wp_update_term($region_name, 'region');

  $term = get_term_by('name', $region_name, 'region');

  // ACF needs the post_ids of the terms(instead of the object)
  $region_leagues = array();
  $leagues = get_terms('league', array('hide_empty' => false));

  foreach($leagues as $league){
    $league_post_id = $league->taxonomy . '_' . $league->term_id;

    if( in_array($league->name, $region['leagues']) ){
      array_push($region_leagues, $league->term_id);
    }
  }

  if( $term ){
    $post_id = 'region_' . $term->term_id;
    $term_acf = $term->taxonomy . '_' . $term->term_id;

    // region_childLeagues field: get previous data
    $leagues_prev = get_field('region_childLeagues', $term_acf);

    foreach($leagues_prev as $league_prev){
      array_push($region_leagues, $league_prev);
    }

    // Remove duplicates
    $region_leagues = array_unique($region_leagues);

    // region_childLeagues field: update
    $field_key_leagues = 'field_570e1122dd70e';
    $value_leagues = $region_leagues;
    update_field( $field_key_leagues, $value_leagues, $post_id );
  }
}

function import_teams(){
  $data = data_get_teams();
  $teams = $data[2];

  foreach($teams as $team){
    $team_name = $team['team'];

    // Check if term already exists
    if(! term_exists($team_name, 'team') ){
      // If not, insert term
      wp_insert_term($team_name, 'team');

      // Check again and run update function
      if( term_exists($team_name, 'team') ){
        import_teams_updateFields($team, $team_name);
      }
    } else {
      wp_update_term($team_name, 'team');

      import_teams_updateFields($team, $team_name);
    }
  }

  import_leagues();
}

function import_teams_updateFields($team, $team_name){
  $term = get_term_by('name', $team_name, 'team');

  if( $term ){
    $post_id = 'team_' . $term->term_id;

    // Corresponding country
    $field_key_country = 'field_5720b15823420';
    $value_country = get_term_by('name', $team['country'], 'region');
    $value_country = $value_country->term_id;
    update_field( $field_key_country, $value_country, $post_id );

    // Corresponding league
    $field_key_league = 'field_5711cc650c343';
    $value_league = get_term_by('name', $team['league'], 'league');
    $value_league = $value_league->term_id;
    update_field( $field_key_league, $value_league, $post_id );

    // Arena name
    $field_key_name = 'field_570772517fb69';
    $value_name = $team['arena_name'];
    update_field( $field_key_name, $value_name, $post_id );

    // Arena city
    $field_key_city = 'field_570772597fb6a';
    $value_city = $team['arena_city'];
    update_field( $field_key_city, $value_city, $post_id );

    // Arena country
    $field_key_country = 'field_570772657fb6b';
    $value_country = $team['arena_country'];
    update_field( $field_key_country, $value_country, $post_id );
  }
}

function import_leagues(){
  $leagues = data_get_leagues();

  foreach($leagues as $league){
    // Get league's name
    $league_name = $league['league'];

    // Check if term already exists
    if(! term_exists($league_name, 'league') ){
      // If not, insert term
      wp_insert_term($league_name, 'league');

      // After insert, check again and run update function
      if( term_exists($league_name, 'league') ){
        import_leagues_updateFields($league, $league_name);
      }
    } else {
      wp_update_term($league_name, 'league');

      import_leagues_updateFields($league, $league_name);
    }
  }
}

function import_leagues_updateFields($league, $league_name){
  $term = get_term_by('name', $league_name, 'league');

  if( $term ){
    $post_id = 'league_' . $term->term_id;

    // ACF needs the post_ids of the terms(instead of the object)
    $league_teams = array();
    $teams = get_terms('team', array('hide_empty' => false));

    foreach( $teams as $team ){
      $team_post_id = $team->taxonomy . '_' . $team->term_id;
      $team_league = get_field('field_5711cc650c343', $team_post_id);

      if( $team_league && $team_league->term_id == $term->term_id ){
        array_push($league_teams, $team->term_id);
      }
    }

    // Remove possible duplicates
    $league_teams = array_unique($league_teams);

    // Update fields
    $field_key_region = 'field_571f3697e9e1d';
    $value_region = get_term_by('name', $league['country'], 'region');
    $value_region = $value_region->term_id;
    update_field( $field_key_region, $value_region, $post_id );

    $field_key_teams = 'field_570e11fbc977c';
    $value_teams = $league_teams;
    update_field( $field_key_teams, $value_teams, $post_id );
  }
}

add_action('init','import_regions');
?>