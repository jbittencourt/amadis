<?

class AMTWebfolio extends AMMain {
  
  const WEBFOLIO_DEFAULT = "top_webfolio.gif"; 
  const WEBFOLIO_MY_WEBFOLIO = "top_meu_webfolio.gif";
  function __construct($type = self::WEBFOLIO_DEFAULT) {
    global $_CMAPP;
    parent::__construct("azul2");
    
    $this->requires("webfolio.css",CMHTMLObj::MEDIA_CSS);
    $this->setImgId($_CMAPP[imlang_url]."/$type");

    $this->openNavMenu();

  }
}



?>