<?php
get_header();

echo '<section class="content-container">';

  $term =	$wp_query->queried_object;
  $term_acf = $term->taxonomy . '_' . $term->term_id;
  /**
   * woocommerce_before_main_content hook.
   *
   * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
   * @hooked woocommerce_breadcrumb - 20
   */
  do_action( 'woocommerce_before_main_content' );

  /*
   * Get children by custom field
   *
   * Reference: https://www.advancedcustomfields.com/resources/get-values-from-a-taxonomy-term/
   */

  $children = get_field('league_children', $term);

  if( $children ):

    echo '<h3>All teams in ' . $term->name . '</h3>';

    woocommerce_product_loop_start();

      foreach($children as $child){
        $child = get_term($child);

        $arena_name = get_field('arena_name', $child);
        $arena_location_city = get_field('arena_location_city', $child);
        $arena_location_country = get_field('arena_location_country', $child);
        ?>

        <li>
          <div class="list-item-40">
            <p><?php echo $child->name; ?></p>
          </div>

          <div class="list-item-20">
            <p><?php echo $arena_name; ?></p>
          </div>

          <div class="list-item-20">
            <p><?php echo $arena_location_city; ?></p>
          </div>

          <div class="list-item-20">
            <a class="button" href="<?php echo get_term_link($child); ?>">view matches</a>
          </div>
        </li>

        <?php
      }

    woocommerce_product_loop_end();
  endif;

echo '</section>';

/**
 * Sidebar
 */
$region = get_field( 'league_region', $term_acf );
$region_id = $region->taxonomy . '_' . $region->term_id;
$region_leagues = get_field( 'region_leagues', $region_id );
?>

<?php if( $region_leagues ): ?>
  <aside>
    <div>
      <h4 class="aside-subheader">Other English leagues</h4>

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
<?php endif;

get_footer();
?>