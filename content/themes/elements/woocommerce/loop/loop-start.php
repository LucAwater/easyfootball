<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

?>

<?php
global $wp_query;
$term =	$wp_query->queried_object;
?>

<?php if( is_page('regions') ): ?>
  <section class="regions">
    <ul class="list list-col">
      <li class="list-col-head">
        <p class="list-item-40">Country</p>
        <p class="list-item-20"></p>
      </li>
<?php elseif( $term && $term->taxonomy == 'region' ): ?>
  <section class="leagues">
    <ul class="list list-card">
<?php elseif( $term && $term->taxonomy == 'league' ): ?>
  <section class="teams">
    <ul class="list list-card list-card-teams">
<?php elseif( $term && $term->taxonomy == 'team' ): ?>
  <section class="matches">
    <ul class="list list-col">
      <li class="list-col-head">
        <p class="list-item-20">Date</p>
        <p class="list-item-40">Match</p>
        <p class="list-item-20">Prices</p>
        <p class="list-item-20"></p>
      </li>
<?php else: ?>
  <section>
    <ul class="list list-col">
<?php endif; ?>
