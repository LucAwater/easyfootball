<?php
function data_get_regions(){
  $data = data_get_teams();
  $regions_data = $data[0];
  $leagues = $data[2];

  // Make 'leagues' array multidimensional
  $regions = array();

  foreach($regions_data as $region){
    array_push($regions, array('region' => $region));
  }

  // Add teams to corresponding leagues
  $a = 0;
  foreach($regions as $region){
    $region_name = $region['region'];
    $region_leagues = array();

    foreach($leagues as $league){
      // If league name matches, push team to 'leagues' array
      if($league['country'] == $region_name && !in_array($league['league'], $region_leagues) ){
        array_push($region_leagues, $league['league']);
      }
    }
    $regions[$a]['leagues'] = $region_leagues;

    $a++;
  }

  return $regions;
}
?>