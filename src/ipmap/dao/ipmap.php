<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace bravedave\ipmap\dao;

use dvc\dao\_dao;

class ipmap extends _dao {
  protected $_db_name = 'ipmap';
  protected $template = __NAMESPACE__ . '\dto\ipmap';

  public function getMatrix(): array {
    $sql = 'SELECT * FROM `ipmap`';
    if ($res = $this->Result($sql)) {
      return $this->dtoSet($res);
    }

    return [];
  }

  public function Insert($a) {
    $a['created'] = $a['updated'] = self::dbTimeStamp();
    return parent::Insert($a);
  }

  public function UpdateByID($a, $id) {
    $a['updated'] = self::dbTimeStamp();
    return parent::UpdateByID($a, $id);
  }
}
