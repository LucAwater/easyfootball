<?php
function data_get_teams(){
  ini_set('auto_detect_line_endings', true);

  $row = 0;
  $regions = array();
  $leagues = array();
  $teams = array();
  $handle = fopen(get_template_directory() . "/data/easyfootball-teams.csv", "r");

  // Loop through rows of csv file
  while (($data = fgetcsv($handle, ";")) !== FALSE) {

    // $data = array_map("utf8_encode", $data);

    // Get column headers
    if( $row == 0 ){
      // $headers = explode(";", $data[0]);
      $headers = $data;
    } else {
      // Get data in one array
      // $content_data = explode(";", $data[0]);
      $content_data = $data;

      for($a = 0; $a < count($content_data); $a++){
        // Pair data with column headers
        $content_data = array_combine($headers, $content_data);
      }

      // Check if region is already in array
      if(! in_array($content_data['country'], $regions) ){
        // Push league to leagues array
        array_push($regions, $content_data['country']);
      }

      // Check if league is already in array
      if(! in_array($content_data['league'], $leagues) ){
        // Push league to leagues array
        array_push($leagues, $content_data['league']);
      }

      // Build teams array
      array_push($teams, $content_data);
    }

    $row++;
  }
  fclose($handle);

  return array($regions, $leagues, $teams);
}
?>