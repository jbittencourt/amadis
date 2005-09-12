<?
class AMColorBox extends AMAbstractBox {

  protected $theme;
  protected $class;
  protected $title;

  const COLOR_BOX_BEGE        = "box_02";
  const COLOR_BOX_BLUE        = "box_01";
  const COLOR_BOX_BLUA        = "box_03";
  const COLOR_BOX_NONE        = "box_01";
  const COLOR_BOX_GREEN       = "box_04";
  const COLOR_BOX_ROSA        = "box_05";
  const COLOR_BOX_LGREEN      = "box_06";
  const COLOR_BOX_GREEN2      = "box_07";
  const COLOR_BOX_BLUEB       = "box_08";
  const COLOR_BOX_PURPLE      = "box_09";
  const COLOR_BOX_DARKPURPLE  = "box_10";
  const COLOR_BOX_YELLOW      = "box_11";
  const COLOR_BOX_YELLOWB     = "box_13";
  const COLOR_BOX_PINK        = "box_14";

  public function __construct($title,$theme){
    parent::__construct("240","left");

    $this->requires("colorbox.css",CMHTMLObj::MEDIA_CSS);
    
    $this->theme = $theme."/".$theme;

    $this->title = $title;

    switch ($theme) {
    case self::COLOR_BOX_BEGE:
      $this->class = "box_bege";
      break;
    case self::COLOR_BOX_BLUE:
      $this->class = "box_blue";
      break;
    case self::COLOR_BOX_BLUEB:
      $this->class = "box_blueb";
      break;
    case self::COLOR_BOX_GREEN:
      $this->class = "box_green";
      break;
    case self::COLOR_BOX_BLUA:
      $this->class = "box_blua";
      break;
    case self::COLOR_BOX_ROSA:
      $this->class = "box_rosa";
      break;
    case self::COLOR_BOX_LGREEN:
      $this->class = "box_lgreen";
      break;
    case self::COLOR_BOX_GREEN2:
      $this->class = "box_green2";
      break;
    case self::COLOR_BOX_PURPLE:
      $this->class = "box_purple";
      break;
    case self::COLOR_BOX_DARKPURPLE:
      $this->class = "box_darkpurple";
      break;
    case self::COLOR_BOX_YELLOW:
      $this->class = "box_yellow";
      break;
    case self::COLOR_BOX_YELLOWB:
      $this->class = "box_yellowb";
      break;
    case self::COLOR_BOX_PINK:
      $this->class = "box_pink";
      break;
    }
  }



  /**
   * Pode-se adicionar uma pilha de strings html em um array 
   * ou um unico string html para ser que irah para a tela
   **/
  public function add($item) {
    $this->contents[] = $item;
  }



  public function __toString() {
    global $_CMAPP;
    
    parent::add("<!-- Begin AMColorBox -->");
    parent::add('<div id="color-box-bounding">');
    parent::add("<table id=\"color-table\" class='color_box $this->class'>");
    parent::add("<tbody>");

    //first line empty
    parent::add("<tr>");
    parent::add("<td id='ctl'><img  src=\"".$_CMAPP[images_url]."/".$this->theme."_es.gif\"></td>");
    parent::add("<td id='bt' ><img   src=\"".$_CMAPP[images_url]."/dot.gif\"></td>");
    parent::add("<td id='ctr'><img  src=\"".$_CMAPP[images_url]."/".$this->theme."_ds.gif\"></td>");
    parent::add("</tr>");
    //end first line empty

    parent::add("<tr>");
    parent::add("<td id='bl'></td>");

    //title image
    if(!empty($this->title)) {
      parent::add("<td valign=\"top\"><img src=\"".$this->title."\" ");
    }
    else {
      parent::add("<td valign=\"top\"><img src=\"".$_CMAPP[images_url]."/dot.gif\" ");
    }
    parent::add("border=\"0\"><br>");
    //end title image

    parent::add("<font class=\"textobox\">");

    //parse itens
    if(!empty($this->contents)) {
	parent::add($this->contents);
    }
    parent::add("</font><td id='br'></td>");
    parent::add("</tr>");
    
    //last line empty
    parent::add("<tr>");
    parent::add("<td id='cbl'><img src=\"".$_CMAPP[images_url]."/".$this->theme."_ei.gif\"></td>");
    parent::add("<td id='bb' ><img src=\"".$_CMAPP[images_url]."/dot.gif\" border=\"0\"></td>");
    parent::add("<td id='cbr'><img  src=\"".$_CMAPP[images_url]."/".$this->theme."_di.gif\"></td>");
    parent::add("</tr>");
    //end last line empty

    parent::add("</tbody></table>");
    parent::add('</div>');
    parent::add("<!-- end AMColorBox -->");

    return parent::__toString();

  }

}


?>