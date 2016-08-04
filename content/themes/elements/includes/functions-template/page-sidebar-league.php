<?php
/**
 * Sidebar
 */
global $wp_query;

$term =	$wp_query->queried_object;
$term_acf = $term->taxonomy . '_' . $term->term_id;

$region = get_field( 'league_parentRegion', $term_acf );
$region_id = $region->taxonomy . '_' . $region->term_id;
$region_leagues = get_field( 'region_childLeagues', $region_id );

// Remove current league from leagues array
$region_leagues = array_diff($region_leagues, array($term->term_id));
?>

<aside>
  <?php if( $region_leagues ): ?>
    <div>
      <h4 class="aside-subheader">Andra ligor i <?php echo $region->name; ?></h4>

      <ul>
        <?php
        foreach($region_leagues as $league):
          $league_term = get_term_by('id', $league, 'league');

          if( $league_term ){
            echo '<li><a href="' . get_term_link($league) . '">' . $league_term->name . '</a></li>';
          }
        endforeach;
        ?>
      </ul>
    </div>
  <?php endif; ?>

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
</aside>
