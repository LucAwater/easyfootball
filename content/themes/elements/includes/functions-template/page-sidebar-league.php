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
?>

<?php if( $region_leagues ): ?>
  <aside>
    <div>
      <h4 class="aside-subheader">Other leagues in <?php echo $region->name; ?></h4>

      <ul>
        <?php
        foreach($region_leagues as $league):
          if( $term->term_id != $league ){
            $league_term = get_term_by('id', $league, 'league');

            echo '<li><a href="' . get_term_link($league) . '">' . $league_term->name . '</a></li>';
          }
        endforeach;
        ?>
      </ul>
    </div>

    <div>
      <h4 class="aside-subheader">Top Games</h4>

      <ul>
        <li>
          <a>Aston Villa - Chelsea</a>
          <small>21 July 2016 at 16:00</small>
        </li>

        <li>
          <a>Manchester United - Barcelona</a>
          <small>21 July 2016 at 16:00</small>
        </li>

        <li>
          <a>Ajax - Real Madrid</a>
          <small>21 July 2016 at 16:00</small>
        </li>
      </ul>
    </div>
  </aside>
<?php endif; ?>