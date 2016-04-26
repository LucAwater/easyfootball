<?php
function data_get_leagues(){
  $data = data_get_teams();
  $leagues_data = $data[1];
  $teams = $data[2];

  // Make 'leagues' array multidimensional
  $leagues = array();

  foreach($leagues_data as $league){
    array_push($leagues, array('league' => $league));
  }

  // Add teams to corresponding leagues
  $a = 0;
  foreach($leagues as $league){
    $league_name = $league['league'];
    $league_teams = array();

    foreach($teams as $team){
      // If league name matches, push team to 'leagues' array
      if($team['league'] == $league_name){
        array_push($league_teams, $team['team']);

        // Add country to league array
        if(! isset($leagues[$a]['country']) ){
          $leagues[$a]['country'] = $team['country'];
        }
      }
    }
    $leagues[$a]['teams'] = $league_teams;

    $a++;
  }

  return $leagues;
}
?>