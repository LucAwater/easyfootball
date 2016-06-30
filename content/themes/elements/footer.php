    <?php page_sidebar(); ?>
  </main>

  <!-- Footer -->
  <footer>
    <div class="footer-content">
      <ul>
        <li>
          <h4>Betalningsmetoder</h4>
          <img src="<?php echo get_template_directory_uri(); ?>/img/logo-payex.png" />
        </li>

        <?php
        $col2_title = get_field('footer_col2_title', 'option');
        $col2_content = get_field('footer_col2_content', 'option');
        if( $col2_title || $col2_content ):
        ?>
          <li>
            <h4><?php echo $col2_title; ?></h4>

            <?php echo $col2_content; ?>
          </li>
        <?php endif; ?>

        <?php
        $col3_title = get_field('footer_col3_title', 'option');
        $col3_content = get_field('footer_col3_content', 'option');
        if( $col3_title || $col3_content ):
        ?>
          <li>
            <h4><?php echo $col3_title; ?></h4>

            <?php echo $col3_content; ?>
          </li>
        <?php endif; ?>

        <?php
        $col4_title = get_field('footer_col4_title', 'option');
        $col4_content = preg_replace('/<p>/', '<small>', get_field('footer_col4_content', 'option'));
        if( $col4_title || $col4_content ):
        ?>
          <li>
            <h4><?php echo $col4_title; ?></h4>

            <?php echo $col4_content; ?>
          </li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="footer-bottom">
      <small>&copy; 2016 Corella Tickets AB. All rights reserved. <a href="http://lucawater.nl" target="_blank">Made by Luc Awater</a></small>
    </div>
  </footer>

  <!-- Scripts -->
  <?php wp_footer(); ?>
</body>
</html>
