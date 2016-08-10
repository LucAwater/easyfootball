<?php
function data_get_regions(){
  $data = data_get_teams();
  $regions_data = $data[0];
  $leagues = $data[2];

  // Make 'leagues' array multidimensional
  $regions = array();

  // Fill regions array
  foreach($regions_data as $region){
    array_push($regions, array('region' => $region));
  }

  // Loop over regions array
  for($x = 0; $x < count($regions); $x++){
    $region = $regions[$x];
    $region_name = $region['region'];
    $region_leagues = array();

    // Loop over leagues
    for($y = 0; $y < count($leagues); $y++){
      $league = $leagues[$y];

      // If league country field matches current regions, add to object
      if($league['country'] === $region_name && !in_array($league['league'], $region_leagues) ){
        array_push($region_leagues, $league['league']);
      }
    }

    // Add all leagues to 'leagues' key in array
    $regions[$x]['leagues'] = $region_leagues;
  }

  return $regions;
}
?>