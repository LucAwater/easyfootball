    <?php page_sidebar(); ?>
  </main>

  <!-- Footer -->
  <footer>
    <div class="footer-content">
      <ul>
        <li>
          <h4>Payment methods</h4>
          <img src="<?php echo get_template_directory_uri(); ?>/img/logo-payex.png" />
        </li>

        <li>
          <h4>Site content</h4>

          <ul>
            <li><a href="<?php echo home_url(); ?>/regions">Regions</a></li>
            <li><a href="<?php echo home_url(); ?>/leagues">Leagues</a></li>
            <li><a href="<?php echo home_url(); ?>/teams">Teams</a></li>
          </ul>
        </li>

        <li>
          <h4>Learn more</h4>
          <ul>
            <li><a href="<?php echo home_url(); ?>/regions">FAQ</a></li>
            <li><a href="<?php echo home_url(); ?>/leagues">Privacy</a></li>
            <li><a href="<?php echo home_url(); ?>/teams">Terms and Conditions</a></li>
          </ul>
        </li>

        <li>
          <h4>Company info</h4>
          <small>Corella Tickets AB</small>
          <small>Regeringsgatan 82</small>
          <small>111 39 Stockholm</small>
          <small>+46 762 215 217</small>
        </li>
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