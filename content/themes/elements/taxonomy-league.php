<?php
get_header();

page_content_start();

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

  $teams = get_field('league_childTeams', $term_acf);

  if( $teams ):

    woocommerce_product_loop_start();

      foreach($teams as $team){
        $team = get_term_by('id', $team, 'team');
        $team_acf = $team->taxonomy . '_' . $team->term_id;
        $team_name = $team->name;
        $team_link = get_term_link($team, 'team');

        $arena_name = get_field('arena_name', $team);
        $arena_location_city = get_field('arena_location_city', $team);
        $arena_location_country = get_field('arena_location_country', $team);

        $team_logo = get_field('team_logo', $team_acf);
        if(! $team_logo ){
          $team_logo_url = get_template_directory_uri() . '/img/placeholder-team.svg';
        } else {
          $team_logo_url = $team_logo['sizes']['medium'];
        }
        ?>

        <li>
          <div class="card-container">
            <figure>
              <img src="<?php echo $team_logo_url; ?>" />
            </figure>

            <h4><?php echo $team_name; ?></h4>
            <a href="<?php echo $team_link; ?>" class="button button-small">View matches</a>
          </div>
        </li>

        <?php
      }

    woocommerce_product_loop_end();
  endif;

page_content_end();

page_sidebar();

get_footer();
?>