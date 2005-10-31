<?


class AMTForum extends AMMain {
  

  function __construct() {
    
    global $_CMAPP;
    parent::__construct("green");

    $this->setImgId($_CMAPP['imlang_url']."/top_forum.gif");
    $this->requires("forum.css",CMHTMLObj::MEDIA_CSS);

    $this->openNavMenu();
  }
}



?>