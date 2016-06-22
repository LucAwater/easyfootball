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

        <li>
          <h4>Site content</h4>

          <ul>
            <li><a href="<?php echo home_url(); ?>/landskampaner">Landskampaner</a></li>
            <li><a href="<?php echo home_url(); ?>/ligor">Ligor</a></li>
            <li><a href="<?php echo home_url(); ?>/lag">Lag</a></li>
          </ul>
        </li>

        <li>
          <h4>Läs mer</h4>
          <ul>
            <li><a href="<?php echo home_url(); ?>/regions">FAQ</a></li>
            <li><a href="<?php echo home_url(); ?>/leagues">Dina personuppgifter</a></li>
            <li><a href="<?php echo home_url(); ?>/teams">Användarvillkor</a></li>
          </ul>
        </li>

        <li>
          <h4>Företagsinformation</h4>
          <small>Corella Tickets AB</small>
          <small>Regeringsgatan 82</small>
          <small>111 39 Stockholm</small>
          <small>Mail: info@easyfootball.se</small>
          <small>Telefon: 08 519 72 728</small>
          <small>Org nr: 556907-1698</small>
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
