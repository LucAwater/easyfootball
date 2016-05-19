<?php
get_header();

if( have_posts() ):
  while( have_posts() ): the_post();

    /**
     * woocommerce_before_main_content hook.
     *
     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
     * @hooked woocommerce_breadcrumb - 20
     */
    do_action( 'woocommerce_before_main_content' );

    /*
     * List all teams by first character
     *
     * Reference: https://www.advancedcustomfields.com/resources/get-values-from-a-taxonomy-term/
     */
    $terms = get_terms('team', array('hide_empty' => false));

    if ( !empty( $terms ) && !is_wp_error( $terms ) ){
      $term_list = [];
      foreach ( $terms as $term ){
        $first_letter = strtoupper($term->name[0]);
        $term_list[$first_letter][] = $term;
      }
      unset($term);

      echo '<section class="content-container">';
        echo '<h3>All teams</h3>';

        echo '<ul class="list list-cloud">';
          foreach ( $term_list as $key=>$value ) {
            echo '<div>';
              echo '<h4>' . $key . '</h4>';

              foreach ( $value as $term ) {
                echo '<li><a href="' . get_term_link( $term ) . '">' . $term->name . '</a></li>';
              }
            echo '</div>';
          }
        echo '</ul>';
      echo '</section>';
    }

    /**
     * Sidebar
     */
    ?>
    <aside>
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

    <?php
  endwhile;
endif;

get_footer();
?>