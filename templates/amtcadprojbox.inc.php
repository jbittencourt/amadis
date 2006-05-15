<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */


class AMTCadProjBox extends AMTCadBox {

  public function __construct($titulo="") {
    parent::__construct();

  }

  public function setTitle($value) {
    global $_CMAPP;
    parent::setTitle("<img src=\"$_CMAPP[imlang_url]/$value\">");

  }

  public function __toString() {
    
    return parent::__toString();
    
  }


}



?>