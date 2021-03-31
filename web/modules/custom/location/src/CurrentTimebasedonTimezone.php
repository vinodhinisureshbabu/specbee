<?php

namespace Drupal\location;


/**
 * Class CurrentTimebasedonTimezone.
 */
class CurrentTimebasedonTimezone {

  /*
    pass timezone from ACF to get current time 
  */
  public function getCurrentTime($timezone){
    $date = new \DateTime("now", new \DateTimeZone($timezone) );
    return $date->format(' j F Y - g:i a');
  }

}
