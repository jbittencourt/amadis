<?

class AMTChat extends AMMain {
  

  function __construct() {
    global $_CMAPP;

    parent::__construct("vermelho");
    
    $this->setImgId($_CMAPP[imlang_url]."/top_chat_amadis.gif"); 
    $this->openNavMenu();
    //$this->requires("divs.js");
  }
}



?>