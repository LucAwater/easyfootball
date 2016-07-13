<?php
function dateFormat($date_raw, $lang = 'sv', $format = '%e %b %G') {
  switch ($lang) {
    case 'sv':
      setlocale(LC_TIME, 'sv_SE.UTF-8');
      $date = strftime($format, $date_raw->getTimestamp());
      $date_day = strftime("%a", $date_raw->getTimestamp());
      break;
    default:
      setlocale(LC_TIME, 'en_US.UTF-8');
      $date = strftime($format, $date_raw->getTimestamp());
      $date_day = strftime("%a", $date_raw->getTimestamp());
  }

  return array($date, $date_day);
}
?>