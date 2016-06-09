<nav>
  <ul>
    <!-- Teams -->
    <li>
      <a href="<?php echo home_url() . '/teams'; ?>">Lag</a>

      <?php
      featured_lists_teams();
      $teams = featured_lists_teams();

      if( $teams ):
        echo '<ul>';
          echo '<div>';

          // Loop over teams
          if( count($teams) >= 15 ){
            $loop_max = 14;
          } else {
            $loop_max = count($teams);
          }

          for( $x = 0; $x < $loop_max; $x++ ){
            $team = get_term_by('id', $teams[$x], 'team');
            $team_acf = $team->taxonomy . '_' . $team->term_id;
            $team_name = $team->name;
            $team_link = get_term_link($team, 'team');

            // Wrap an extra container around every 5 list items
            if( $x > 0 && $x %5 == 0 ){
              echo '</div><div>';
            }

            // Create list item
            echo '<li><a href="' . $team_link . '">' . $team_name . '</a></li>';
          }

          // View all link
          echo '<li class="view-more"><a class="button button-small" href="' . home_url() . '/teams">view more teams</a></li>';

        echo '</ul>';
      endif; ?>
    </li>

    <!-- Leagues -->
    <li>
      <a href="<?php echo home_url() . '/leagues'; ?>">Ligor</a>

      <?php
      featured_lists_leagues();
      $leagues = featured_lists_leagues();

      if( $leagues ):
        echo '<ul>';
          echo '<div>';

          // Loop over leagues
          if( count($leagues) >= 10 ){
            $loop_max = 9;
          } else {
            $loop_max = count($leagues);
          }

          for( $x = 0; $x < $loop_max; $x++ ){
            $league = get_term_by('id', $leagues[$x], 'league');
            $league_acf = $league->taxonomy . '_' . $league->term_id;
            $league_name = $league->name;
            $league_link = get_term_link($league, 'league');

            // Wrap an extra container around every 5 list items
            if( $x > 0 && $x %5 == 0 ){
              echo '</div><div>';
            }

            // Create list item
            echo '<li><a href="' . $league_link . '">' . $league_name . '</a></li>';
          }

          // View all link
          echo '<li class="view-more"><a class="button button-small" href="' . home_url() . '/leagues">view more leagues</a></li>';

        echo '</ul>';
      endif; ?>
    </li>



    <!-- Regions -->
    <li>
      <a href="<?php echo home_url() . '/regions'; ?>">Land</a>

      <?php
      $regions = get_terms( 'region', array('hide_empty' => false,) );

      if( $regions ):
        echo '<ul>';

          foreach( $regions as $region ){
            $region_name = $region->name;
            $region_link = get_term_link($region, 'region');

            echo '<li><a href="' . $region_link . '">' . $region_name . '</a>';
          }

        echo '</ul>';
      endif;
      ?>
    </li>

  </ul>
</nav>