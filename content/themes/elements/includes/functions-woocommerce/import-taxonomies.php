<?php
function import_taxonomies() {
  /*
   * Insert regions
   */
  $regions = data_get_regions();

  foreach($regions as $region){
    wp_insert_term($region, 'region');
  }

  /*
   * Insert teams
   */
  $teams = data_get_teams();

  foreach($teams as $team){
    $team_name = $team['team'];

    // Check if term already exists
    if(! term_exists($team_name, 'team') ){
      // If not, insert term
      wp_insert_term($team_name, 'team');
    } else {
      $term = get_term_by('name', $team_name, 'team');
      if( $term ){
        $post_id = 'team_' . $term->term_id;

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

  }

  /*
   * Insert leagues
   */
  $leagues = data_get_leagues();

  foreach($leagues as $league){
    // Get league's name
    $league_name = $league['league'];

    // Check if term already exists
    if(! term_exists($league_name, 'league') ){
      // If not, insert term
      wp_insert_term($league_name, 'league');

      // After insert, check again
      if( term_exists($league_name, 'league') ){

      }
    } else {
      wp_update_term($league_name, 'league');

      $term = get_term_by('name', $league_name, 'league');
      $post_id = 'league_' . $term->term_id;

      /*
       * Update ACF field
       *
       * Reference: https://www.advancedcustomfields.com/resources/update_field/
       */
      $league_teams = array();
      $teams = get_terms('team', array('hide_empty' => false));

      $i = 0;
      foreach( $teams as $team ){
        $team_post_id = $team->taxonomy . '_' . $team->term_id;
        $team_league = get_field('field_5711cc650c343', $team_post_id);

        if( $team_league && $team_league->term_id == $term->term_id ){
          array_push($league_teams, $team->term_id);
        }
      }

      $field_key_teams = 'field_570e11fbc977c';
      $value_teams = $league_teams;
      update_field( $field_key_teams, $value_teams, $post_id );
    }

  }

}
add_action('init','import_taxonomies');
?>