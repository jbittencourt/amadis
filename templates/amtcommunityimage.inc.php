<?
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */


class AMTCommunityImage extends AMImageTemplate {
 

  public function __toString() {
    global $_CMAPP;

    $url = $this->getImageURL();
    parent::add("<img src=\"$url\" class=\"boxcomunidade\">");
    
    return parent::__toString();
  } 

}


?>