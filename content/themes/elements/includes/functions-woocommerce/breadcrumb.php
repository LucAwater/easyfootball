<?php
function get_breadcrumb(){
  global $wp_query;
  $term =	$wp_query->queried_object;

  if( $term->taxonomy == 'region' ){
    $region = $term->name;
    ?>

    <nav itemscope itemtype="http://schema.org/BreadcrumbList">
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <p itemprop="item"><span itemprop="name"><?php echo $region; ?></span></p>
        <meta itemprop="position" content="1" />
      </li>
    </nav>

  <?php } elseif( $term->taxonomy == 'league' ){
    $post_id = $term->taxonomy . '_' . $term->term_id;
    $region = get_field('league_region', $post_id);
    $region = $region->name;
    $league = $term->name;
    ?>
    <nav itemscope itemtype="http://schema.org/BreadcrumbList">
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/region"><span itemprop="name"><?php echo $region; ?></span></a>
        <meta itemprop="position" content="1" />
      </li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <p itemprop="item"><span itemprop="name"><?php echo $league; ?></span></p>
        <meta itemprop="position" content="2" />
      </li>
    </nav>

  <?php } elseif( $term->taxonomy == 'team' ){
    $post_id = $term->taxonomy . '_' . $term->term_id;
    $region = get_field('arena_location_country', $post_id);
    $league = get_field('league', $post_id);
    $league = $league->name;
    $team = $term->name;
    ?>
    <nav itemscope itemtype="http://schema.org/BreadcrumbList">
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/region"><span itemprop="name"><?php echo $region; ?></span></a>
        <meta itemprop="position" content="1" />
      </li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/region/league"><span itemprop="name"><?php echo $league; ?></span></a>
        <meta itemprop="position" content="2" />
      </li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <p itemprop="item"><span itemprop="name"><?php echo $team; ?></span></p>
        <meta itemprop="position" content="3" />
      </li>
    </nav>

  <?php } elseif( is_product() ){
    $match = get_the_title();
    ?>
    <nav itemscope itemtype="http://schema.org/BreadcrumbList">
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/region"><span itemprop="name">region</span></a>
        <meta itemprop="position" content="1" />
      </li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/region/league"><span itemprop="name">league</span></a>
        <meta itemprop="position" content="2" />
      </li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/region/league/team"><span itemprop="name">team</span></a>
        <meta itemprop="position" content="3" />
      </li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <p itemprop="item"><span itemprop="name"><?php echo $match; ?></span></p>
        <meta itemprop="position" content="4" />
      </li>
    </nav>
  <?php
  }
}
?>