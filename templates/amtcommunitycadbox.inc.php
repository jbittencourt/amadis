<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */


class AMTCommunityCadBox extends AMTCadBox {

  public function __construct($title) {
    parent::__construct($title, AMTCadBox::CADBOX_CREATE, AMTCadBox::COMMUNITY_THEME);
  }
 
}



?>