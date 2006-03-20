<?

class AMWRichTextArea extends CMWFormEl {
  protected static $_initialized=false;

  protected $width;
  protected $height;

  function __construct($name,$value="",$width=400,$height=200) {
    $this->requires("richtext.js",self::MEDIA_JS);
    $this->requires("html2xhtml.js",self::MEDIA_JS);
    $this->requires("rte.css",self::MEDIA_CSS);


    parent::__construct($name,$value,"textarea");

    $this->setName($name);
    $this->width = $width;
    $this->height = $height;
    $this->prop['value'] = $value;
  }


  function safeContent() {    
    //returns safe code for preloading in the RTE
    $tmpString = $this->prop['value'];


    //Convert all types of single quotes
    $tmpString = str_replace(chr(145), chr(39), $tmpString);
    $tmpString = str_replace(chr(146), chr(39), $tmpString);
    $tmpString = str_replace("'", "&#39;", $tmpString);

    //convert all types of double quotes
    $tmpString = str_replace(chr(147), chr(34), $tmpString);
    $tmpString = str_replace(chr(148), chr(34), $tmpString);
    //      $tmpString = str_replace("\"", "\"", $tmpString);

    //replace carriage returns & line feeds
    $tmpString = str_replace(chr(10), " ", $tmpString);
    $tmpString = str_replace(chr(13), " ", $tmpString);
    return $tmpString;
  }

  public function __toString() {
    global $_CMAPP;

    if ($this->design == CMWFormEl::WFORMEL_DESIGN_LEFT_TWO_COLS) {
      $this->add($this->label);
      $this->add("</TD><TD>");
    }
    else {
      if($this->design != CMWFormEl::WFORMEL_DESIGN_STRING_DEFINED) $this->add($this->label);
      if($this->design == CMWFormEl::WFORMEL_DESIGN_OVER) $this->add("<br>");
    }

    if(!self::$_initialized) {
      $this->addPageBegin("<script>initRTE('$_CMAPP[images_url]/rte/', '$_CMAPP[media_url]/rte/', '', true);</script>");
      self::$_initialized = true;
    }

    $this->addScript("writeRichText('$this->name','".$this->safeContent()."', $this->width, $this->height, true, false);");
    $this->addPageEnd("<script>initPalleteDlg('$this->name')</script>");

    $this->parentForm->addOnSubmitAction("updateRTE('$this->name')");

    return parent::__toString();
  }

}

?>