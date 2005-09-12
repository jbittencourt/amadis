<?

class AMDotLine extends CMHtmlObj {
 
  private $fullWidth;

  const FULL_WIDTH = TRUE;

  public function __construct($fullWidth=true) {
    parent::__construct();
    $this->fullWidth = $fullWidth;
  }

  public function __toString() {
    global $_CMAPP;
    
    if($this->fullWidth) {
      parent::add('<div id="dashed-line">');
      parent::add("<img src=\"".$_CMAPP[images_url]."/dot.gif\" width=2></div>");
    } else {
      parent::add("<img src=\"".$_CMAPP[images_url]."/box_traco.gif\">");
    }
    return parent::__toString();
  }

}

?>
