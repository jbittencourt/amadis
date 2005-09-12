<?


class AMComunidade extends AMMain {
  

  function __construct() {
    global $urlimagens, $urlimlang;
    parent::__construct();


    $this->setImgId("$urlimlang/img_tit_comunidade.gif");

    $this->openNavMenu();
  }


}



?>