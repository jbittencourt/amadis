<?
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */


class AMTCommunities extends AMMain {
  

  function __construct() {
    
    global $_CMAPP;
    parent::__construct("green");

    $this->setImgId($_CMAPP['imlang_url']."/top_comunidades.gif");
    $this->requires("communities.css",CMHTMLObj::MEDIA_CSS);
    $this->openNavMenu();
  }
}



?>