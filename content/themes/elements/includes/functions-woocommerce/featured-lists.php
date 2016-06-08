<?php
function featured_lists_events() {
  return $events = get_field('top_events', 'option');
}

function featured_lists_teams() {
  return $teams = get_field('top_teams', 'option');
}

function featured_lists_leagues() {
  return $leagues = get_field('top_leagues', 'option');
}
?>