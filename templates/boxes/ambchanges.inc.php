<?


class AMBChanges extends AMColorBox {


  public function __construct() {
    global $_CMAPP;
    parent::__construct($_CMAPP[imlang_url]."/box_novidades_amadis.gif",self::COLOR_BOX_BEGE);
  }

  public function __toString() {
    global $_language, $_CMAPP;




    return parent::__toString();
  }

}



?>