<?php
add_action( 'after_setup_theme', 'rss_template_matches' );

/**
* Register custom RSS template.
*/
function rss_template_matches() {
  add_feed( 'matches', 'rss_matches' );
  add_feed( 'matches-bundesliga', 'rss_matches_bundesliga' );
  add_feed( 'matches-championship', 'rss_matches_championship' );
  add_feed( 'matches-championsLeague', 'rss_matches_championsLeague' );
  add_feed( 'matches-laLiga', 'rss_matches_laLiga' );
  add_feed( 'matches-ligue1', 'rss_matches_ligue1' );
  add_feed( 'matches-premierLeague', 'rss_matches_premierLeague' );
  add_feed( 'matches-serieA', 'rss_matches_serieA' );
}

/**
* Custom RSS template callback.
*/
function rss_matches() {
  get_template_part( 'feeds/feed', 'matches' );
}

function rss_matches_bundesliga() { get_template_part( 'feeds/feed', 'bundesliga' ); }
function rss_matches_championship() { get_template_part( 'feeds/feed', 'championship' ); }
function rss_matches_championsLeague() { get_template_part( 'feeds/feed', 'championsLeague' ); }
function rss_matches_laLiga() { get_template_part( 'feeds/feed', 'laLiga' ); }
function rss_matches_ligue1() { get_template_part( 'feeds/feed', 'ligue1' ); }
function rss_matches_premierLeague() { get_template_part( 'feeds/feed', 'premierLeague' ); }
function rss_matches_serieA() { get_template_part( 'feeds/feed', 'serieA' ); }
?>