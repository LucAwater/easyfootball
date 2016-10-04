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
          <h2><?php _e('Topplag'); ?></h2>
        </section>

        <div class="section-body">
          <ul class="list list-card">
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
                'meta_key'    => 'match_date',
                'orderby'     => 'meta_value',
                'order'       => 'asc',
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
                    <p class="card-subtitle"><?php _e('Toppmatcher'); ?></p>

                    <?php if( $team_matches): ?>
                      <ul>
                        <?php
                        foreach( $team_matches as $post ):
                          setup_postdata( $post );
                          $match_name = get_the_title();
                          $match_link = get_the_permalink();

                          $match_time = get_field('match_time');
                          $match_date_raw = get_field('match_date', false, false);
                          $date_regex = '/[0-9]{4}-[0-9]{2}-[0-9]{2}/';
                          if($match_date_raw && preg_match($date_regex, $match_date_raw)){
                            $match_date_raw = DateTime::createFromFormat('Y-m-j', $match_date_raw);
                            $match_date = dateFormat($match_date_raw, 'sv', '%e %B %G')[0];
                          }
                          ?>

                          <li>
                            <a href="<?php echo $match_link; ?>"><?php echo $match_name; ?></a>
                            <small><?php echo (isset($match_date)) ? $match_date : ''; ?><?php echo ($match_time) ? ', ' . $match_time : ''; ?></small>
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
                    <a class="button" href="<?php echo $team_link; ?>"><?php _e('visa alla matcher'); ?></a>
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
          <h2><?php _e('Toppmatcher'); ?></h2>
        </div>

        <div class="section-body">
          <ul class="list list-card">
            <?php
            for( $x = 0; $x < 6; $x++ ){
              $event = get_post($events[$x]);
              $event_name = $event->post_title;
              $event_link = get_permalink($event->ID);

              $event_time = get_post_meta($event->ID, 'match_time', true);
              $event_date_raw = get_post_meta($event->ID, 'match_date', true);
              $date_regex = '/[0-9]{4}-[0-9]{2}-[0-9]{2}/';
              if($event_date_raw && preg_match($date_regex, $event_date_raw)){
                $event_date_raw = DateTime::createFromFormat('Y-m-j', $event_date_raw);
                $event_date = dateFormat($event_date_raw)[0];
              }

              $event_location = get_post_meta($event->ID, 'match_location', true);
              $event_location = get_term_by('name', $event_location, 'team');

              $event_location_acf = $event_location->taxonomy . '_' . $event_location->term_id;
              $event_location_logo = get_field('team_logo', $event_location_acf);
              $arena_location_city = get_field('arena_location_city', $event_location_acf);
              $arena_location_country = get_field('arena_location_country', $event_location_acf);
              $arena_location = $arena_location_city . ', ' . $arena_location_country;

              $_event = wc_get_product( $event->ID );
              $event_price = $_event->get_price();

              $team_logo = get_field('team_logo', $event_location_acf);
              if(! $team_logo ){
                $team_logo_url = get_template_directory_uri() . '/img/placeholder-team.svg';
              } else {
                $team_logo_url = $team_logo['sizes']['medium'];
              }
              ?>

              <li>
                <div class="card-container">
                  <figure class="card-image">
                    <a href="<?php echo $event_link; ?>">
                      <img src="<?php echo $team_logo_url; ?>" />
                    </a>
                  </figure>

                  <div class="card-info">
                    <a href="<?php echo $event_link; ?>"><h4 class="card-title"><?php echo $event_name; ?></h4></a>
                    <small class="card-subtitle"><?php echo (isset($event_date)) ? $event_date : ''; ?><?php echo ($event_time) ? ', ' . $event_time : ''; ?><?php echo ($arena_location) ? ' – ' . $arena_location : ''; ?></small>
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
