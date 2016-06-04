<?php
get_header();

if( have_posts() ):
  while( have_posts() ): the_post();

    // Featured events
    featured_lists_events();
    $events = featured_lists_events();

    if( $events ){
      ?>

      <section class="featured featured-events">
        <ul class="list list-card">

          <?php
          foreach( $events as $event ){
            $event_name = $event->post_title;
            $event_link = get_permalink($event->ID);

            echo '<li><a href="' . $event_link . '">' . $event_name . '</a></li>';
          }
          ?>

        </ul>
      </section>

      <?php
    }

    // Featured teams
    featured_lists_teams();
    $teams = featured_lists_teams();

    if( $teams ){
      ?>

      <section class="featured featured-teams">
        <h2 class="is-aligned-center">Top Teams</h2>

        <ul class="list list-card">
          <?php
          foreach( $teams as $team ){
            $team = get_term_by('id', $team, 'team');
            $team_acf = $team->taxonomy . '_' . $team->term_id;
            $team_name = $team->name;
            $team_link = get_term_link($team->term_id);
            $team_logo = get_field('team_logo', $team_acf);
            $team_logo_url = $team_logo['sizes']['medium'];

            $team_matches = get_posts(array(
              'post_type' => 'product',
              'numberposts' => 3,
              'tax_query' => array(
                array(
                  'taxonomy' => 'team',
                  'field' => 'id',
                  'terms' => $team->term_id,
                  'include_children' => false
                )
              )
            ));

            ?>

            <li>
              <div class="card-container">
                <figure class="card-image">
                  <img src="<?php echo $team_logo_url; ?>" />
                </figure>

                <div class="card-info">
                  <h3><?php echo $team_name; ?></h3>
                  <p>Top matches</p>

                  <?php if( $team_matches): ?>
                    <ul>
                      <?php
                      foreach( $team_matches as $post ):
                        setup_postdata( $post );
                        $match_name = get_the_title();
                        $match_link = get_the_permalink();
                        $match_date = get_field('match_date', false, false);
                        $match_date = new DateTime($match_date);
                        $match_date = $match_date->format('j F Y');
                        $match_time = get_field('match_time');
                        ?>

                        <li>
                          <a href="<?php echo $match_link; ?>"><?php echo $match_name; ?></a>
                          <small><?php echo ($match_date) ? $match_date : ''; ?><?php echo ($match_time) ? ' at ' . $match_time : ''; ?></small>
                        </li>

                        <?php
                        wp_reset_postdata();
                      endforeach;
                      ?>
                    </ul>
                  <?php endif; ?>
                </div>

                <div class="card-actions">
                  <p><?php echo count($team_matches); ?> in total</p>
                  <a class="button">view all matches</a>
                </div>
              </div>
            </li>

            <?php
          }
          ?>

        </ul>
      </section>

      <?php
    }

  endwhile;
endif;

get_footer();
?>