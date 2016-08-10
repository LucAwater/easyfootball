<?php
$image = get_field("intro_image");
$image_url = $image["sizes"]["large"];
?>

<!-- Intro section for homepage w/ search form -->
<section
  class="page-header page-header-home page-header-image"
  id="intro-home"
  style="background-image: url(<?php echo $image_url; ?>)">
  <h1>Easyfootball.se</h1>
  <h3><?php _e('Köp fotbollsbiljetter till världens alla matcher'); ?></h3>

  <form role="search" method="get" action="<?php echo home_url( '/' ); ?>">
    <input type="search" pattern=".{3,}" title="3 characters minimum" required placeholder="<?php echo esc_attr_x( 'Sök lag, ligor, platser...', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" />

    <button type="submit">Sök</button>
  </form>
</section>
