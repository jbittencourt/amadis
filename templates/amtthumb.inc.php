<?


class AMTThumb extends CMHTMLObj {

  protected $filename;

  public function __construct($filename) {
    parent::__construct();
    $this->filename = $filename;
  }

  public function getThumbURL() {
    global $_CMAPP;
    return $_CMAPP[thumbs_url]."/$this->filename";
  }

  public function __toString() {
    global $_CMAPP;
    
    parent::add("<img id=\"thumb\" src=\"$_CMAPP[thumbs_url]/$this->filename\">");
    
    return parent::__toString();
  
  }



}



?>