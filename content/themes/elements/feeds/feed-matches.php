<?php
/**
 * Customs RSS template with related posts.
 *
 * Place this file in your theme's directory.
 *
 * @package elements
 */

/**
 * Feed defaults.
 */
header( 'Content-Type: ' . feed_content_type( 'rss-http' ) . '; charset=' . get_option( 'blog_charset' ), true );
$frequency  = 1;        // Default '1'. The frequency of RSS updates within the update period.
$duration   = 'daily'; // Default 'hourly'. Accepts 'hourly', 'daily', 'weekly', 'monthly', 'yearly'.
$postlink   = '<br /><a href="' . get_permalink() . '">Visa alla matcher</a><br /><br />';
$email      = get_the_author_meta( 'email');
$author     = get_the_author();
$postimages = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );

// Check for post image. If none, fallback to a default.
$postimage = ( $postimages ) ? $postimages[0] : get_stylesheet_directory_uri() . '/images/default.jpg';

/**
 * Start RSS feed.
 */
echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '"?' . '>'; ?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	<?php do_action( 'rss2_ns' ); ?>
>

	<!-- RSS feed defaults -->
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
        $price = $product->price;
        ?>
        <item>
          <title><?php the_title_rss(); ?></title>
  				<price><?php echo 'Från ' . $price; ?></price>
  				<link><?php the_permalink_rss(); ?></link>
  				<guid isPermaLink="false"><?php the_guid(); ?></guid>
  				<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>

  				<!-- Echo content and related posts -->
  				<content:encoded>
  					<![CDATA[<?php echo the_excerpt_rss(); echo $postlink; echo my_rss_related(); ?>]]>
  				</content:encoded>
  			</item>
        <?php
        wp_reset_postdata();
      endforeach;
    endif;
    ?>
    <!-- End product loop -->


	</channel>
</rss>