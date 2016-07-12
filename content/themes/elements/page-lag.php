<?php get_header(); ?>

<?php page_content_start(); ?>
  <?php
  /*
   * List all teams by first character
   *
   * Reference: https://www.advancedcustomfields.com/resources/get-values-from-a-taxonomy-term/
   */
  $terms = get_terms('team', array('hide_empty' => false));

  if ( !empty( $terms ) && !is_wp_error( $terms ) ):
    $term_list = [];
    foreach ( $terms as $term ){
      $first_letter = strtoupper($term->name[0]);
      $term_list[$first_letter][] = $term;
    }
    unset($term);
    ?>
    <section>
      <h3>Alla lag</h3>

      <ul class="list list-cloud">
        <?php foreach ( $term_list as $key=>$value ): ?>
          <div>
            <h4><?php echo $key; ?></h4>

            <?php foreach ( $value as $term ): ?>
              <li><a href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?></a></li>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </ul>
    </section>
  <?php endif; ?>
<?php page_content_end(); ?>

<?php page_sidebar(); ?>

<?php get_footer(); ?>