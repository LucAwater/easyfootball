<!-- Intro section for homepage w/ search form -->
<section class="page-header page-header-home" id="intro-home">
  <h1>hitta din fotbollsmatch</h1>

  <form role="search" method="get" action="<?php echo home_url( '/' ); ?>">
    <input type="search" pattern=".{3,}" title="3 characters minimum" required placeholder="<?php echo esc_attr_x( 'Sök lag, ligor, platser...', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" />

    <button type="submit">Sök</button>
  </form>
</section>