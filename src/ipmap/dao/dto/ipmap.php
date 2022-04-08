<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace bravedave\ipmap\dao\dto;

use dvc\dao\dto\_dto;

class ipmap extends _dto {
  public $id = 0;
  public $created = '';
  public $updated = '';

  public $hostname = '';
  public $ip = '';
  public $description = '';
}
