<?
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */

class AMNenhum extends AMMain {
  

  function __construct($mostratitulo="1") {
    global $_CMAPP
    parent::__construct();


    $this->setMenuSuperior($_CMAPP[images_url]."/bg_diario.gif",
			   $_CMAPP[images_url]."/img_diario_01.jpg",
			   $_CMAPP[images_url]."/img_diario_sombra.gif");


    $this->setImgId($_CMAPP[imlang_url]."/img_top_diario.gif");

    $this->openNavMenu();
  }
}



?>