<?php

/**
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMAlbum
 * @version 1.0
 * @author Cristiano S Basso <csbasso@lec.ufrgs.br>
 */

class AMAlbumThumb extends AMThumb {

  public function __construct() {
    parent::__construct();
    $this->maxX = 135;
    $this->maxY = 120;
  }

}