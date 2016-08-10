    <?php page_sidebar(); ?>
  </main>

  <!-- Footer -->
  <footer>
    <div class="footer-content">
      <ul>
        <?php
        $col1_title = get_field('footer_col1_title', 'option');
        $col1_images = get_field('footer_col1_images', 'option');
        if( $col1_title || $col1_images ):
        ?>
          <li>
            <h4><?php echo $col1_title; ?></h4>

            <?php
            foreach($col1_images as $image){
              $image_url = $image['sizes']['medium'];
              $image_width = $image['sizes']['medium-width'];
              $image_height = $image['sizes']['medium-height'];

              echo '<img src="' . $image_url . '" width="' . $image_width . '" height="' . $image_height . '" />';
            }
            ?>
          </li>
        <?php endif; ?>

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
        $col4_content = preg_replace('/<p>(.+?)<\/p>/is', '<small>$1</small>', get_field('footer_col4_content', 'option'));
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
