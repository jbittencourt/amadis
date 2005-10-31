<?

class AMTOficinas extends AMMain {
  
  function __construct() {
    global $_CMAPP;
    parent::__construct("roxinho");

    $this->setImgId($_CMAPP['imlang_url']."/img_tit_oficinas.gif");

    $this->openNavMenu();
  }


}



?>