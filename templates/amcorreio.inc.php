<?
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */

class AMCorreio extends AMMain {
  

  function __construct() {
    global $urlimagens, $urlimlang;
    parent::__construct("azul");


    $this->setImgId("$urlimlang/img_tit_correio.gif");
    $this->openNavMenu();

  }
}



?>