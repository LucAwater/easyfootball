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
      $post_id = 'team_' . $term->term_id;

      // Arena name
      $field_key = 'field_570772517fb69';
      $value = $team['arena_name'];
      update_field( $field_key, $value, $post_id );

      // Arena city
      $field_key = 'field_570772597fb6a';
      $value = $team['arena_city'];
      update_field( $field_key, $value, $post_id );

      // Arena country
      $field_key = 'field_570772657fb6b';
      $value = $team['arena_country'];
      update_field( $field_key, $value, $post_id );
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
      $term = get_term_by('name', $league_name, 'league');

      /*
       * Update ACF field
       *
       * Reference: https://www.advancedcustomfields.com/resources/update_field/
       */
      $field_key = 'field_570e17a5ea6c6';
      $post_id = 'league_' . $term->term_id;
      $value = 'test';
      update_field( $field_key, $value, $post_id );
    }

  }

}
add_action('init','import_taxonomies');
?>