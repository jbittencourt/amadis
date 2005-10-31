<?

class AMTAdmin extends AMMain {
  

  function __construct() {
    global $_CMAPP;
    parent::__construct("verde");
    
    $this->setImgId($_CMAPP['imlang_url']."/img_tit_admin.gif");

    $this->openNavMenu();
  }


}



?>