<?php
/**
 * RSS2 Feed Template for displaying RSS2 Posts feed.
 *
 * @package WordPress
 */

header('Content-Type: ' . feed_content_type('rss2') . '; charset=' . get_option('blog_charset'), true);
$more = 1;
$frequency  = 1;        // Default '1'. The frequency of RSS updates within the update period.
$duration   = 'daily'; // Default 'hourly'. Accepts 'hourly', 'daily', 'weekly', 'monthly', 'yearly'.
$postlink   = '<br /><a href="' . get_permalink() . '">Visa alla matcher</a><br /><br />';
$email      = get_the_author_meta( 'email');
$author     = get_the_author();
$postimages = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );

// Check for post image. If none, fallback to a default.
$postimage = ( $postimages ) ? $postimages[0] : get_stylesheet_directory_uri() . '/images/default.jpg';

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';

/**
 * Fires between the xml and rss tags in a feed.
 *
 * @since 4.0.0
 *
 * @param string $context Type of feed. Possible values include 'rss2', 'rss2-comments',
 *                        'rdf', 'atom', and 'atom-comments'.
 */
do_action( 'rss_tag_pre', 'rss2' );
?>

<rss version="2.0"
  xmlns:content="http://purl.org/rss/1.0/modules/content/"
  xmlns:wfw="http://wellformedweb.org/CommentAPI/"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:atom="http://www.w3.org/2005/Atom"
  xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
  xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
  <?php
  /**
   * Fires at the end of the RSS root to add namespaces.
   *
   * @since 2.0.0
   */
  do_action( 'rss2_ns' );
  ?>
>
  <channel>
    <title>Easyfootball</title>
    <link><?php bloginfo_rss( 'url' ) ?></link>
    <lastBuildDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_lastpostmodified( 'GMT' ), false ); ?></lastBuildDate>
    <language><?php bloginfo_rss( 'language' ); ?></language>
    <sy:updatePeriod><?php echo apply_filters( 'rss_update_period', $duration ); ?></sy:updatePeriod>
    <sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', $frequency ); ?></sy:updateFrequency>
    <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />

    <?php do_action( 'rss2_head' ); ?>

    <!-- Start product loop -->
    <?php
    global $product;

    // Get posts and order by custom field 'match_date'
    $matches = get_posts(array(
      'post_type'   => 'product',
      'numberposts' => -1,
      'meta_key'    => 'match_date',
      'orderby'     => 'meta_value',
      'order'       => 'asc'
    ));

    if( $matches ):
      foreach( $matches as $post ):
        setup_postdata( $post );

        $product = new WC_Product( $post->ID );

        // Get match minimum price
        $price = $product->price;

        if( $price == 0 ){
          $price = "På förfrågan";
        }

        // Get match date
        $match_date_raw = get_field('match_date', false, false);

        // Get home and away team
        $teams = wp_get_post_terms($post->ID, 'team');
        $team_home = get_field('match_location', false, false);

        foreach( $teams as $team ){
          if( $team->name != $team_home ){
            $team_away = $team->name;
          }
        }

        if( $team_home && $team_away && $match_date_raw && $price ):
          ?>
          <item>
            <home><?php echo $team_home; ?></home>
            <away><?php echo $team_away; ?></away>
            <date><?php print($match_date_raw); ?></date>
            <price><?php echo $price; ?></price>
            <link><?php the_permalink_rss(); ?></link>
          </item>
          <?php
        endif;

        wp_reset_postdata();
      endforeach;
    endif;
    ?>
    <!-- End product loop -->
  </channel>
</rss>
