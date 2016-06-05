<nav>
  <ul>
    <!-- Teams -->
    <li>
      <a href="<?php echo home_url() . '/teams'; ?>">Lag</a>
      <ul>
        <li><p>Arsenal</p></li>
        <li><p>Aston Villa</p></li>
        <li><p>Birmingham</p></li>
        <li><p>Bournemouth</p></li>
        <li><p>Chelsea</p></li>
        <li class="view-more"><a href="<?php echo home_url() . '/teams'; ?>">view more teams</a></li>
      </ul>
    </li>

    <!-- Leagues -->
    <li><a href="<?php echo home_url() . '/leagues'; ?>">Ligor</a></li>



    <!-- Regions -->
    <li>
      <a href="<?php echo home_url() . '/regions'; ?>">Land</a>

      <?php
      $regions = get_terms( 'region', array('hide_empty' => false,) );

      if( $regions ):
        echo '<ul>';

          foreach( $regions as $region ){
            $region_name = $region->name;
            $region_link = get_term_link($region->term_id, 'region');

            echo '<li><a href="' . $region_link . '">' . $region_name . '</a>';
          }

          echo '<li class="view-more"><a href="' . home_url() . '/regions">View all regions</a>';
        echo '</ul>';
      endif;
      ?>
    </li>

  </ul>
</nav>