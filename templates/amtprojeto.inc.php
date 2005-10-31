<?

class AMTProjeto extends AMMain {
  

  function __construct() {
    global $_CMAPP;
    parent::__construct();

    $this->requires("project.css",CMHTMLObj::MEDIA_CSS);
    $this->setImgId($_CMAPP['imlang_url']."/top_projetos.gif");

    $this->openNavMenu();


  }
}



?>