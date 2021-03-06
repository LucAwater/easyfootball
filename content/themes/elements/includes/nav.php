<nav>
  <ul>
    <!-- Teams -->
    <li>
      <a href="<?php echo home_url() . '/lag'; ?>">Lag</a>

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
          echo '<li class="view-more"><a class="button button-small" href="' . home_url() . '/lag">Visa alla lag</a></li>';

        echo '</ul>';
      endif; ?>
    </li>

    <!-- Leagues -->
    <li>
      <a href="<?php echo home_url() . '/ligor'; ?>">Ligor</a>

      <?php
      $leagues = get_terms( 'league', array('hide_empty' => false,) );

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
            $league = $leagues[$x];
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
          echo '<li class="view-more"><a class="button button-small" href="' . home_url() . '/ligor">Visa alla ligor</a></li>';

        echo '</ul>';
      endif; ?>
    </li>



    <!-- Regions -->
    <li>
      <a href="<?php echo home_url() . '/landskampaner'; ?>">Land</a>

      <?php
      $regions = get_terms( 'region', array('hide_empty' => false,) );

      if( $regions ):
        echo '<ul>';
          echo '<div>';

          // Loop over regions
          $loop_max = count($regions);

          for( $x = 0; $x < $loop_max; $x++ ){
            $region = $regions[$x];
            $region_name = $region->name;
            $region_link = get_term_link($region, 'region');

            // Wrap an extra container around every 5 list items
            if( $x > 0 && $x %5 == 0 ){
              echo '</div><div>';
            }

            echo '<li><a href="' . $region_link . '">' . $region_name . '</a>';
          }

        echo '</ul>';
      endif;
      ?>
    </li>

    <li>
      <p>Mer</p>

      <ul>
        <?php
        $nav_args = array(
          'theme_location'  => 'menu_secondary',
          'container'       => '',
          'items_wrap'      => '%3$s'
        );
        wp_nav_menu( $nav_args );
        ?>
      </ul>
    </li>

  </ul>
</nav>
