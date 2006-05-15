<?
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */

class AMTAgregador extends AMMain {
  

  function __construct() {
    global $_CMAPP;
    parent::__construct("bege");

    $this->requires("project.css",self::MEDIA_CSS);
    $this->requires("diary.css",self::MEDIA_CSS);

    $this->setImgId($_CMAPP['imlang_url']."/top_projetos.gif");
    
    $this->openNavMenu();
  }
}



?>