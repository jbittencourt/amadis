<?


class AMTCadCommunity extends AMMain {
  

  function __construct() {
    global $_CMAPP;
    parent::__construct("azul2");

    $this->setImgId($_CMAPP[imlang_url]."/top_cadastro_comunidade.gif");

    $this->openNavMenu();

  }
}



?>