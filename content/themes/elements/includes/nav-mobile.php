<nav id="navMobile">
  <div>
    <a>close <i class="material-icons">&#xE5CD;</i></a>
  </div>

  <div id="navMobile-menu">
    <div>
      <form role="search" method="get" action="<?php echo home_url( '/' ); ?>">
        <input type="search" pattern=".{3,}" title="3 characters minimum" required placeholder="<?php echo esc_attr_x( 'Sök lag, ligor, platser...', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" />

        <button type="submit"><img src="<?php echo get_template_directory_uri(); ?>/img/icon-search-grey.svg" /></button>
      </form>
    </div>

    <?php include('nav.php'); ?>
  </div>

  <div>
    <div>
      <a class="button button-sec button-service" href="<?php echo home_url(); ?>/kundtjanst"><i class="material-icons">&#xE0CA;</i> Kundtjänst</a>
    </div>
    <small><i class="material-icons">&#xE0BE;</i><a href="mailto:info@easyfootball.se"> info@easyfootball.se</a></small>
    <small><i class="material-icons">&#xE0CD;</i> 08 519 72 728</small>
  </div>
</nav>