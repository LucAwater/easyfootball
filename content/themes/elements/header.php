<?php
/*
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the main tag.
 */
?>

<!DOCTYPE html>
<!--[if IE 9]>    <html class="no-js lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
  <title>Easy Football & Events</title>

  <link rel="canonical" href="<?php echo home_url(); ?>">

  <!-- META TAGS -->
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="description" content="">

  <meta property="og:title" content="">
  <meta property="og:site_name" content="">
  <meta property="og:description" content="">
  <meta property="og:image" content="">
  <meta property="og:url" content="">

  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Fonts from Typography.com -->
  <link rel="stylesheet" type="text/css" href="https://cloud.typography.com/6711094/6333752/css/fonts.css" />
  <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">

  <!-- Stylesheet -->
  <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/app.css">

  <!-- WP_HEAD() -->
  <?php wp_head(); ?>
</head>

<?php
// Detect page headers
if( is_tax() || is_product() || is_search() ){
  $body_class = "has-page-header";
} else if( is_front_page() ){
  $body_class = "has-page-header has-page-header-image";
} else if( is_page('kundtjanst') ){
  $body_class = "has-page-header has-page-header-color";
} else {
  $body_class = "";
}
?>

<body <?php body_class($body_class); ?>>
  <!-- Header -->
  <header>
    <div class="header-content">
      <a class="link-logo" href="<?php echo home_url(); ?>">
        <img src="<?php echo bloginfo( 'template_directory' ); ?>/img/logo-test.svg">
        <img src="<?php echo bloginfo( 'template_directory' ); ?>/img/logo-test2.svg">
        <img src="<?php echo bloginfo( 'template_directory' ); ?>/img/logo-test-white.svg">
        <!-- <h2>EasyFootball</h2> -->
      </a>

      <?php if(! is_front_page() ): ?>
        <form role="search" method="get" action="<?php echo home_url( '/' ); ?>">
          <input type="search" pattern=".{3,}" title="3 characters minimum" required placeholder="<?php echo esc_attr_x( 'Sök lag, ligor, platser...', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" />

          <button type="submit"><img src="<?php echo get_template_directory_uri(); ?>/img/icon-search-black.svg" /></button>
        </form>
      <?php endif; ?>

      <?php include_once( 'includes/nav.php' ); ?>

      <a class="button button-sec" href="<?php echo home_url(); ?>/kundtjanst">Kundtjänst</a>
    </div>
  </header>

  <?php breadcrumb(); ?>

  <?php page_header(); ?>

  <!-- Main content -->
  <main role="main">
