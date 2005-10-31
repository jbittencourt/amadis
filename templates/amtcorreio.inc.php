<?

include($_CMAPP[path]."/templates/ammain.inc.php");


class AMTCorreio extends AMMain {
  

  function __construct() {
    global $_CMAPP;
    parent::__construct("bege");

    $this->setImgId($_CMAPP['imlang_url']."/top_correio.gif");

    $this->openNavMenu();
  }
}



?>