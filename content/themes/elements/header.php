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

  <!-- Icon font from Google -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!-- Stylesheet -->
  <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/app.css">

  <!-- Favicons -->
  <link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/img/favicon-152.png">
  <meta name="msapplication-TileColor" content="#FFFFFF">
  <meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/img/favicon-144.png">

  <!-- WP_HEAD() -->
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <!-- Header -->
  <header>
    <div class="header-content">
      <div>
        <a class="link-logo" href="<?php echo home_url(); ?>">
          <?php if( is_front_page() ): ?>
            <img src="<?php echo bloginfo( 'template_directory' ); ?>/img/logo-white.svg">
          <?php else: ?>
            <img src="<?php echo bloginfo( 'template_directory' ); ?>/img/logo-color.svg">
          <?php endif; ?>
        </a>

        <?php if(! is_front_page() ): ?>
          <form role="search" method="get" action="<?php echo home_url( '/' ); ?>">
            <input type="search" pattern=".{3,}" title="3 characters minimum" required placeholder="<?php echo esc_attr_x( 'Sök lag, ligor, platser...', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" />

            <button type="submit"><img src="<?php echo get_template_directory_uri(); ?>/img/icon-search-grey.svg" /></button>
          </form>
        <?php endif; ?>

        <?php include( 'includes/nav.php' ); ?>

        <a class="button button-sec button-service" href="<?php echo home_url(); ?>/kundtjanst"><i class="material-icons">&#xE0CA;</i> Kundtjänst</a>
        <a id="navMobile-open" class="button button-sec button-menu"><i class="material-icons">&#xE5D2;</i> Meny</a>
      </div>
    </div>

    <div class="header-bottom">
      <div>
        <?php breadcrumb(); ?>

        <div>
          <small><i class="material-icons">&#xE0BE;</i><a href="mailto:info@easyfootball.se"> info@easyfootball.se</a></small>
          <small><i class="material-icons">&#xE0CD;</i> 08 519 72 728</small>
        </div>
      </div>
    </div>
  </header>

  <!-- Mobile nav -->
  <?php include_once('includes/nav-mobile.php'); ?>

  <!-- Page header -->
  <?php page_header(); ?>

  <!-- Main content -->
  <main role="main">
