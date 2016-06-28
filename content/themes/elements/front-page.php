<?php
get_header();

if( have_posts() ):
  while( have_posts() ): the_post();

    global $product, $woocommerce_loop;

    // Featured teams
    featured_lists_teams();
    $teams = featured_lists_teams();

    if( $teams ){
      ?>

      <section class="featured featured-teams">
        <section class="section-header">
          <h2><?php _e('Top Teams'); ?></h2>
        </section>

        <div class="section-body">
          <ul class="list list-card list-card-shields">
            <?php
            for( $x = 0; $x < 6; $x++ ){
              $team = get_term_by('id', $teams[$x], 'team');
              $team_acf = $team->taxonomy . '_' . $team->term_id;
              $team_name = $team->name;
              $team_link = get_term_link($team, 'team');

              $team_logo = get_field('team_logo', $team_acf);
              if(! $team_logo ){
                $team_logo_url = get_template_directory_uri() . '/img/placeholder-team.svg';
              } else {
                $team_logo_url = $team_logo['sizes']['medium'];
              }

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
                    <a href="<?php echo $team_link; ?>">
                      <img src="<?php echo $team_logo_url; ?>" />
                    </a>
                  </figure>

                  <div class="card-info">
                    <a href="<?php echo $team_link; ?>"><h3><?php echo $team_name; ?></h3></a>
                    <p class="card-subtitle"><?php _e('Top matches'); ?></p>

                    <?php if( $team_matches): ?>
                      <ul>
                        <?php
                        foreach( $team_matches as $post ):
                          setup_postdata( $post );
                          $match_name = get_the_title();
                          $match_link = get_the_permalink();
                          $match_date = get_field('match_date', false, false);
                          $match_date = DateTime::createFromFormat('Y-m-j', $match_date);
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
                    <p><?php echo $team->count . __(' in total'); ?></p>
                    <a class="button" href="<?php echo $team_link; ?>"><?php _e('se alla matcher'); ?></a>
                  </div>
                </div>
              </li>

              <?php
            }
            ?>
          </ul>
        </div>
      </section>

      <?php
    }

    // Featured events
    featured_lists_events();
    $events = featured_lists_events();

    if( $events ){
      ?>

      <section class="featured featured-events">
        <div class="section-header">
          <h2><?php _e('Top Matches'); ?></h2>
        </div>

        <div class="section-body">
          <ul class="list list-card list-card-matches">
            <?php
            for( $x = 0; $x < 6; $x++ ){
              $event = get_post($events[$x]);
              $event_name = $event->post_title;
              $event_link = get_permalink($event->ID);

              $event_date = get_post_meta($event->ID, 'match_date', true);
              $event_date = DateTime::createFromFormat('Y-m-j', $event_date);
              $event_date = $event_date->format('j F Y');
              $event_time = get_post_meta($event->ID, 'match_time', true);

              $event_location = get_post_meta($event->ID, 'match_location', true);
              $event_location = get_term_by('name', $event_location, 'team');

              $event_location_acf = $event_location->taxonomy . '_' . $event_location->term_id;
              $arena_location_city = get_field('arena_location_city', $event_location_acf);
              $arena_location_country = get_field('arena_location_country', $event_location_acf);
              $arena_location = $arena_location_city . ', ' . $arena_location_country;

              $_event = wc_get_product( $event->ID );
              $event_price = $_event->get_price();

              $event_teams = wp_get_post_terms($event->ID, 'team');
              ?>

              <li>
                <div class="card-container">
                  <figure class="card-image">
                    <a href="<?php echo $event_link; ?>">
                      <?php team_logos($event_location, $event_teams); ?>
                      <span>VS</span>
                    </a>
                  </figure>

                  <div class="card-info">
                    <a href="<?php echo $event_link; ?>"><h4 class="card-title"><?php echo $event_name; ?></h4></a>
                    <small class="card-subtitle"><?php echo ($event_date) ? $event_date : ''; ?><?php echo ($event_time) ? ' at ' . $event_time : ''; ?><?php echo ($arena_location) ? ' – ' . $arena_location : ''; ?></small>
                  </div>

                  <div class="card-actions">
                    <p><?php echo ($event_price) ? _e('Från ') . $event_price : ''; ?></p>
                    <a href="<?php echo $event_link; ?>" class="button"><?php _e('Köpa biljetter'); ?></a>
                  </div>
                </div>
              </li>

              <?php
            }
            ?>
          </ul>
        </div>
      </section>

      <?php
    }



  endwhile;
endif;

get_footer();
?>
