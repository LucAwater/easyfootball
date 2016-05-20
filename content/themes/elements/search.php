<?php
get_header();

// Get search query and convert into slug-like format
$searchQuery = get_search_query();
$searchQuery = strtolower($searchQuery);
$searchQuery = preg_replace('/\s+/', '-', $searchQuery);

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

page_content_start();

  // List results
  if( $results_leagues ):

    echo '<div class="results results-leagues">';
      echo '<h2>Leagues</h2>';
      echo '<ul>';

        foreach( $results_leagues as $result ):
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
   * Get TEAMS that match search criteria
   */
  $teams = get_terms('team', array('hide_empty' => false));
  $results_teams = array();

  // Check if searchQuery matches (piece of) team slug and push to results array
  if( $teams ):
    foreach( $teams as $team ):
      if (strpos($team->slug, $searchQuery) !== false) {
        array_push($results_teams, $team);
      }
    endforeach;
  endif;

  // List results
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



  if( !$results_leagues && !$results_teams ):

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