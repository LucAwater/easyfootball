<!-- Intro section for homepage w/ search form -->
<section class="page-header page-header-home" id="intro-home">
  <h1>Find your football match</h1>

  <form role="search" method="get" action="<?php echo home_url( '/' ); ?>">
    <input type="search" placeholder="<?php echo esc_attr_x( 'Search by league, team, competition...', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" />

    <button type="submit">search</button>
  </form>
</section>