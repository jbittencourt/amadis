<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMTCadBox extends CMHTMLObj {

  const DEFAULT_THEME = 1;
  const PROJECT_THEME = 2;
  const COMMUNITY_THEME = 3;
  const PEOPLE_THEME = 4;
  const DIARY_THEME = 5;
  const WEBFOLIO_THEME = 6;

  const CADBOX_SEARCH = "_busca";
  const CADBOX_LIST = "_listar";
  const CADBOX_CREATE = "_criar";
  const CADBOX_DEFAULT = "";

  protected $image, $titulo, $theme, $class, $titlecss;
  protected $buffer = array();
				 
  public function __construct($titulo="", $image=self::CADBOX_DEFAULT, $theme=AMTCadBox::DEFAULT_THEME) {
    parent::__construct();
  
    $this->setTitle($titulo);
    $this->requires("cadbox.css",CMHTMLObj::MEDIA_CSS);

    switch($theme) {
    case AMTCadBox::DEFAULT_THEME:
      $this->theme = "box_cadastro";
      $this->image = "box_cadproj_01$image.gif";
      $this->class = 'cad-box-default';
      break;
    case AMTCadBox::WEBFOLIO_THEME:
      $this->titlecss = 'webfolio-title';
      $this->theme = "box_cadwebfolio";
      $this->image = 'box_cadwebfolio'.$image.'.gif';
      $this->class = 'cad-box-webfolio';
      break;
    case AMTCadBox::PROJECT_THEME:
      $this->titlecss = "project_title";
      $this->theme = "box_cadproj";
      $this->image = "box_cadproj_01$image.gif";
      $this->class = 'cad-box-project';
      break;
    case AMTCadBox::COMMUNITY_THEME:
      $this->titlecss = "txttitcomunidade";
      $this->theme = "box_cad_comunidade";
      $this->image = "box_cad_comunidade_01$image.gif";
      $this->class = 'cad-box-community';
      break;
    case AMTCadBox::PEOPLE_THEME:
      $this->titlecss = "people_title";
      $this->theme = "box_cad_pessoas";
      $this->image = "box_cad_pessoas_01$image.gif";
      $this->class = 'cad-box-people';
      break;
    case AMTCadBox::DIARY_THEME:
      $this->titlecss = "diary_title";
      $this->theme = "box_cad_diario";
      $this->image = "box_cad_diario_01$image.gif";
      $this->class = 'cad-box-diary';
      break;

    }
  }

  public function add($value) {
    $this->buffer[] = $value;
  }


  public function setTitle($value) {
    global $_CMAPP;
    $this->titulo = $value;

  }

  public function __toString() {
    global $_CMAPP;


    parent::add("<!-- inicio do cadBox -->");
    parent::add("<table id='cad-box' class='cad-box $this->class'>");
    parent::add("<tr>");

    parent::add("<td id='ctl'><img src=\"$_CMAPP[images_url]/".$this->image."\"></td>");
    parent::add("<td id='bt' background=\"$_CMAPP[images_url]/".$this->theme."_bgtop.gif\" class='cad-box-cols-top'>");
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"8\" border=\"0\"><span class=\"$this->titlecss\" id='cadbox-title'>");
    parent::add($this->titulo);
    parent::add("</td>");
    parent::add("<td id='ctr'><img src=\"$_CMAPP[images_url]/".$this->theme."_02.gif\"></td>");
    parent::add("</tr>");

    parent::add("<tr>");
    parent::add("<td id='bl' class='cad-box-cols-sides' background=\"$_CMAPP[images_url]/".$this->theme."_bgleft.gif\">");
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"1\"></td>");
    parent::add("<td>");

    parent::add($this->buffer);

    parent::add("</td>");
    parent::add("<td id='br' class='cad-box-cols-sides' background=\"$_CMAPP[images_url]/".$this->theme."_bgright.gif\">");
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"1\" border=\"0\"></td>");
    parent::add("</tr>");

    parent::add("<tr>");
    parent::add("<td id='cbl'><img src=\"$_CMAPP[images_url]/".$this->theme."_03.gif\"></td>");
    parent::add("<td id='bb' class='cad-box-cols-top' background=\"$_CMAPP[images_url]/".$this->theme."_bgbottom.gif\">");
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"1\" border=\"0\"></td>");
    parent::add("<td id='cbr'><img src=\"$_CMAPP[images_url]/".$this->theme."_04.gif\"></td>");
    parent::add("</tr>");
    parent::add("</table>");

    parent::add("<!-- fim do cadBox -->");
    
    return parent::__toString();
  }


}



?>