<?php
get_header();

// Get search query and convert into slug-like format
$searchQuery = get_search_query();
$searchQuery = strtolower($searchQuery);
$searchQuery = preg_replace('/\s+/', '-', $searchQuery);

page_content_start();

  /*
   * Get LEAGUES that match search criteria
   */
  $leagues = get_terms('league', array('hide_empty' => false));
  $results_leagues = array();

  // Check if searchQuery matches (piece of) team slug and push to results array
  if( $leagues ):
    foreach( $leagues as $league ):
      if (strpos($league->slug, $searchQuery) !== false) {
        array_push($results_leagues, $league);
      }
    endforeach;
  endif;

  // List league results
  if( $results_leagues ):

    echo '<div class="results results-leagues">';
      echo '<h2>Leagues</h2>';
      echo '<ul>';

        foreach( $results_leagues as $result ):
          // Check if search query against team slug
          $name = $result->name;
          $name = str_replace($searchQuery, "<strong>" . $searchQuery . "</strong>", $name);
          $bodytag = str_replace("%body%", "black", "<body text='%body%'>");
          $url = get_category_link( $result->term_id );

          echo '<li><a href="' . $url . '">' . $name . '</a></li>';
        endforeach;

      echo '</ul>';
    echo '</div>';

  endif;

  /*
   * Get TEAMS that match search criteria
   */
  $teams = get_terms('team', array('hide_empty' => false));
  $results_teams = array();
  $results_locations = array();

  // Check if searchQuery matches (piece of) team slug and push to results array
  if( $teams ):
    foreach( $teams as $team ):
      if (strpos($team->slug, $searchQuery) !== false) {
        array_push($results_teams, $team);
      }
    endforeach;
  endif;

  // List team results
  if( $results_teams ):

    echo '<div class="results results-teams">';
      echo '<h2>Teams</h2>';
      echo '<ul>';

        foreach( $results_teams as $result ):
          // Check if search query against team slug
          $name = $result->name;
          // $name = preg_replace("/\p{L}*?".preg_quote($searchQuery)."\p{L}*/ui", "<b>$0</b>", $name);
          $url = get_category_link( $result->term_id );

          echo '<li><a href="' . $url . '">' . $name . '</a></li>';
        endforeach;

      echo '</ul>';
    echo '</div>';

  endif;

  /*
   * Get LOCATIONS that match search criteria
   */
  // Check if searchQuery matches (piece of) team "arena_location_city" field and push to results array
  if( $teams ):
    foreach( $teams as $team ):
      $team_acf = $team->taxonomy . '_' . $team->term_id;
      $team_location = get_field('arena_location_city', $team_acf);

      if(stripos($team_location, $searchQuery) !== false) {
        array_push($results_locations, $team->name);
      }
    endforeach;
  endif;

  if( $results_locations ):
    // List location results
    $matches_args = array(
      'post_type'   => 'product',
      'posts_per_page' => 10,
      'order'       => 'asc',
      'meta_query'  => array(
        array(
          'key'     => 'match_location',
          'value'   => $results_locations,
          'compare' => 'IN',
        )
      )
    );
    $matches = new WP_Query($matches_args);

    if( $matches->have_posts() ):
      ?>

      <section class="matches">
        <h3>Upcoming matches in <?php echo $searchQuery; ?></h3>
        <ul class="list list-col">
          <li class="list-col-head">
            <p class="list-item-20">Date</p>
            <p class="list-item-40">Match</p>
            <p class="list-item-20">Prices</p>
            <p class="list-item-20"></p>
          </li>

          <?php
          while( $matches->have_posts() ): $matches->the_post();
            wc_get_template_part( 'woocommerce/content', 'product' );
          endwhile;

        echo '</ul>';
      echo '</section>';

    endif;

    wp_reset_query();

  endif;

  // No results
  if( !$results_leagues && !$results_teams && !$results_locations ):

    echo
    '<div class="no-results">
      <p>Sorry, no matches found for <strong>' . $searchQuery . '</strong></p>
      <p>Check the spelling or try different/fewer keywords and try again</p>
    </div>';

  endif;
page_content_end();

page_sidebar();

get_footer();
?>