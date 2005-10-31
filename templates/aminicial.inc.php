<?
include($_CMAPP['path']."/templates/ammain.inc.php");
class AMInicial extends AMMain {
  

  function __construct() {
    global $_CMAPP;
    parent::__construct("green");

    $this->setImgId($_CMAPP['imlang_url']."/top_apresentacao.gif");

    $this->openNavMenu();
  }
}



?>