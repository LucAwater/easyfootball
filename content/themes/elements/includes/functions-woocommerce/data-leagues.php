<?php
function data_get_leagues(){
  ini_set('auto_detect_line_endings', true);

  $row = 0;
  $leagues = array();
  $handle = fopen(get_template_directory() . "/data/easyfootball-leagues.csv", "r");

  // Loop through rows of csv file
  while (($data = fgetcsv($handle, ";")) !== FALSE) {

    $data = array_map("utf8_encode", $data);

    // Get column headers
    if( $row == 0 ){
      $headers = explode(";", $data[0]);
    } else {
      // Get data in one array
      $content_data = explode(";", $data[0]);

      for($a = 0; $a < count($content_data); $a++){

        // Remove empty cells
        if( empty($content_data[$a]) ){
          unset($content_data[$a]);
        }

        // Pair data with column headers
        $content_data = array_combine($headers, $content_data);

      }

      // Push data to content array
      array_push($leagues, $content_data);
    }

    $row++;
  }
  fclose($handle);

  return $leagues;
}
?>