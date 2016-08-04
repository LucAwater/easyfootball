<aside>
  <div>
    <?php
    // Featured events
    featured_lists_events();
    $events = featured_lists_events();

    if( $events ){
      ?>
      <h4 class="aside-subheader">Toppmatcher</h4>

      <ul>
        <?php
        for( $x = 0; $x < 4; $x++ ){
          $event = get_post($events[$x]);
          $event_name = $event->post_title;
          $event_link = get_permalink($event->ID);

          $event_date = get_post_meta($event->ID, 'match_date', false, false);
          $event_date_raw = DateTime::createFromFormat('Y-m-j', $event_date[0]);
          $event_date = dateFormat($event_date_raw, "sv", "%e %B %G")[0];
          $event_time = get_post_meta($event->ID, 'match_time', true);
          ?>
          <li>
            <a href="<?php echo $event_link; ?>"><?php echo $event_name; ?></a>
            <small><?php echo ($event_date) ? $event_date : ''; ?><?php echo ($event_time) ? ' at ' . $event_time : ''; ?></small>
          </li>
        <?php } ?>
      </ul>
    <?php } ?>
  </div>

  <div class="aside-teams">
    <?php
    // Featured events
    featured_lists_teams();
    $teams = featured_lists_teams();

    if( $teams ){
      ?>
      <h4 class="aside-subheader">Topplag</h4>

      <ul>
        <?php
        for( $x = 0; $x < 4; $x++ ){
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
          ?>
          <li>
            <img src="<?php echo $team_logo_url; ?>" />
            <a href="<?php echo $team_link; ?>"><?php echo $team_name; ?></a>
          </li>
        <?php } ?>
      </ul>
    <?php } ?>
  </div>
</aside>
