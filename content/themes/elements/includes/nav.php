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
          for( $x = 0; $x < 14; $x++ ){
            $team = get_term_by('id', $teams[$x], 'team');
            $team_acf = $team->taxonomy . '_' . $team->term_id;
            $team_name = $team->name;
            $team_link = get_term_link($team->term_id);

            // Wrap an extra container around every 5 list items
            if( $x > 0 && $x %5 == 0 ){
              echo '</div><div>';
            }

            // Create list item
            echo '<li><a href="' . $team_link . '">' . $team_name . '</a></li>';
          }

          // View all link
          if( count($teams) >= 15 ){
            echo '<li class="view-more"><a class="button button-small" href="' . home_url() . '/teams">view more teams</a></li>';
          }

        echo '</ul>';
      endif; ?>
    </li>

    <!-- Leagues -->
    <li><a href="<?php echo home_url() . '/leagues'; ?>">Ligor</a></li>



    <!-- Regions -->
    <li>
      <a href="<?php echo home_url() . '/regions'; ?>">Land</a>

      <?php
      $regions = get_terms( 'region', array('hide_empty' => false,) );

      if( $regions ):
        echo '<ul>';

          foreach( $regions as $region ){
            $region_name = $region->name;
            $region_link = get_term_link($region->term_id, 'region');

            echo '<li><a href="' . $region_link . '">' . $region_name . '</a>';
          }

          echo '<li class="view-more"><a href="' . home_url() . '/regions">View all regions</a>';
        echo '</ul>';
      endif;
      ?>
    </li>

  </ul>
</nav>