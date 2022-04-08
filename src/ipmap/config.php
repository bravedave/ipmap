<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace bravedave\ipmap;

class config extends \config {  // noting: config extends global config classes
  const ipmap_db_version = 1;

  const label = 'ipmap';  // general label for application

  static function ipmap_checkdatabase() {
    $dao = new dao\dbinfo;
    // $dao->debug = true;
    $dao->checkVersion('ipmap', self::ipmap_db_version);
  }
}
