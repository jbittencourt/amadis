<?


class AMTPeople extends AMMain {
  

  function __construct() {
    
    global $_CMAPP;
    parent::__construct("green");
    
    $this->setImgId($_CMAPP['imlang_url']."/top_pessoas.gif");
    $this->requires("people.css",CMHTMLObj::MEDIA_CSS);


    $this->openNavMenu();
  }
}



?>