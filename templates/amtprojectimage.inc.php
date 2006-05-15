<?
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */


class AMTProjectImage extends AMImageTemplate {
 

  public function __toString() {
    global $_CMAPP;

    $url = $this->getImageURL();
    parent::add("<img src=\"$url\" class=\"project_box\">");
    
    return parent::__toString();
  } 

}


?>