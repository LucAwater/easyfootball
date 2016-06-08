<?php
function get_breadcrumb(){
  global $wp_query;
  $term =	$wp_query->queried_object;

  if( $term->taxonomy == 'region' ){
    $region = $term->name;
    ?>

    <nav id="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <p itemprop="item"><span itemprop="name"><?php echo $region; ?></span></p>
        <meta itemprop="position" content="1" />
      </li>
    </nav>

  <?php } elseif( $term->taxonomy == 'league' ){
    $post_id = $term->taxonomy . '_' . $term->term_id;

    $region = get_field('league_region', $post_id);
    $region_name = $region->name;
    $region_slug = $region->slug;

    $league_name = $term->name;
    ?>
    <nav id="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="<?php echo home_url() . '/region/' . $region_slug; ?>"><span itemprop="name"><?php echo $region_name; ?></span></a> /
        <meta itemprop="position" content="1" />
      </li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <p itemprop="item"><span itemprop="name"><?php echo $league_name; ?></span></p>
        <meta itemprop="position" content="2" />
      </li>
    </nav>

  <?php } elseif( $term->taxonomy == 'team' ){
    $post_id = $term->taxonomy . '_' . $term->term_id;

    $region_name = get_field('arena_location_country', $post_id);
    $region_slug = preg_replace('/\s+/', '-', $region_name);

    $leagues = get_field('team_parentLeagues', $post_id);
    $league_name = $leagues[0]->name;
    $league_slug = $leagues[0]->slug;

    $team_name = $term->name;
    ?>
    <nav id="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="<?php echo home_url() . '/region/' . $region_slug; ?>"><span itemprop="name"><?php echo $region_name; ?></span></a> /
        <meta itemprop="position" content="1" />
      </li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="<?php echo home_url() . '/league/' . $league_slug; ?>"><span itemprop="name"><?php echo $league_name; ?></span></a> /
        <meta itemprop="position" content="2" />
      </li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <p itemprop="item"><span itemprop="name"><?php echo $team_name; ?></span></p>
        <meta itemprop="position" content="3" />
      </li>
    </nav>

  <?php } elseif( is_product() ){
    $match_name = get_the_title();

    $regions = get_the_terms( get_the_ID(), 'region' );
    $region_name = $regions[0]->name;
    $region_slug = $regions[0]->slug;

    $leagues = get_the_terms( get_the_ID(), 'league' );
    $league_name = $leagues[0]->name;
    $league_slug = $leagues[0]->slug;

    $teams = get_the_terms( get_the_ID(), 'team' );
    $team_name = $teams[0]->name;
    $team_slug = $teams[0]->slug;
    ?>
    <nav id="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="<?php echo home_url() . '/region/' . $region_slug; ?>"><span itemprop="name"><?php echo $region_name; ?></span></a> /
        <meta itemprop="position" content="1" />
      </li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="<?php echo home_url() . '/league/' . $league_slug; ?>"><span itemprop="name"><?php echo $league_name; ?></span></a> /
        <meta itemprop="position" content="2" />
      </li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="<?php echo home_url() . '/team/' . $team_slug; ?>"><span itemprop="name"><?php echo $team_name; ?></span></a> /
        <meta itemprop="position" content="3" />
      </li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <p itemprop="item"><span itemprop="name"><?php echo $match_name; ?></span></p>
        <meta itemprop="position" content="4" />
      </li>
    </nav>
  <?php
  }
}
?>