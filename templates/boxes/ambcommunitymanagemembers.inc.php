<?

/**
 * Manage members of the community
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Cristiano S. Basso <csbasso@lec.ufrgs.br>
 */


class AMBCommunityManageMembers extends AMBox {

  const COMMUNITY_THEME = 3;
  const PEOPLE_THEME = 4;


  const CADBOX_LIST = "_listar";
  const CADBOX_DEFAULT = "";

  protected $image, $titulo, $theme, $color, $titlecss;
  protected $buffer = array();
				 
  public function __construct($titulo="", $image=self::CADBOX_DEFAULT, $theme=AMTCadBox::DEFAULT_THEME) {
    parent::__construct();
  
    $this->setTitle($titulo);
    
    switch($theme) {
    case AMTCadBox::DEFAULT_THEME:
      $this->theme = "box_cadproj";
      $this->titlecss = "txttitproj";
      $this->color = "#fafcfe";
      $this->image = "box_cadproj_01$image.gif";
      break;
    case AMTCadBox::COMMUNITY_THEME:
      $this->titlecss = "txttitcomunidade";
      $this->theme = "box_cad_comunidade";
      $this->color = "#fbfbec";
      $this->image = "box_cad_comunidade_01$image.gif";
      break;
    case AMTCadBox::PEOPLE_THEME:
      $this->titlecss = "titpessoas";
      $this->theme = "box_cad_pessoas";
      $this->color = "#fff5f5";
      $this->image = "box_cad_pessoas_01$image.gif";
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
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"20\" border=\"0\">");
    parent::add("    <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">");
    parent::add("       <tr>");

    parent::add("       <td width=\"49\" valign=\"top\"><img src=\"$_CMAPP[images_url]/".$this->image."\" width=\"49\" height=\"33\" border=\"0\"></td>");
    parent::add("       <td background=\"$_CMAPP[images_url]/".$this->theme."_bgtop.gif\" valign=\"top\" class=\"$this->titlecss\">");
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"8\" border=\"0\"><br>");
    parent::add("       $this->titulo");
    parent::add("       </td>");
    parent::add("       <td width=\"49\" valign=\"top\"><img src=\"$_CMAPP[images_url]/".$this->theme."_02.gif\" width=\"49\" height=\"33\" border=\"0\"></td>");
    parent::add("       </tr>");
    parent::add("       <tr>");
    parent::add("       <td background=\"$_CMAPP[images_url]/".$this->theme."_bgleft.gif\" valign=\"top\">");
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"1\" border=\"0\"></td>");
    parent::add("       <td valign=\"top\" bgcolor=\"$this->color\">");

    if(!empty($this->buffer)) {
      foreach($this->buffer as $item) {
	parent::add($item);
      }
    }

    parent::add("       </td>");
    parent::add("       <td width=\"32\" background=\"$_CMAPP[images_url]/".$this->theme."_bgright.gif\" valign=\"top\">");
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"1\" border=\"0\"></td>");
    parent::add("       </tr>");
    parent::add("       <tr>");

    parent::add("       <td><img src=\"$_CMAPP[images_url]/".$this->theme."_03.gif\" width=\"49\" height=\"33\" border=\"0\"></td>");
    parent::add("       <td background=\"$_CMAPP[images_url]/".$this->theme."_bgbottom.gif\">");
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"1\" border=\"0\"></td>");
    parent::add("       <td><img src=\"$_CMAPP[images_url]/".$this->theme."_04.gif\" width=\"49\" height=\"33\" border=\"0\"></td>");
    parent::add("       </tr>");
    parent::add("       </table>");

    parent::add("<!-- fim do cadBox -->");
    
    return parent::__toString();
  }


}



?>