<?
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBProjectsCommunity extends CMHTMLObj {

  private $communities = array();

  public function __construct() {

    parent::__construct();
    $this->communities = $_SESSION['environment']->listCommunities();

  }
  
  public function __toString() {

    global $_CMAPP;
    
    $_language = $_CMAPP['i18n']->getTranslationArray("projects");

    /*
     * URL de submit do form
     */
    $url = $_CMAPP['services_url']."/projetos/listprojects.php";
    
    /*
     *Buffering html of the box to output screen
     */
    $buffer  = "<table cellspacing=0 cellpadding=0 border=0>\n";
    $buffer .= "<form action=$url method=post name=frm_prjtCommunity>\n";
    $buffer .= "<tr>\n";
    $buffer .= "<td>\n";
    $buffer .= "<img src=\"".$_CMAPP['imlang_url']."/img_projetos_comunidade.gif\" border=\"0\"><br>";
    $buffer .= "<img src=\"".$_CMAPP['images_url']."/dot.gif\" border=\"0\" height=\"7\" width=\"1\"><br>\n";
    $buffer .= "<select onChange=\"document.frm_prjtCommunity.submit();\" ";
    $buffer .= "name=\"frm_codeCommunity\" style=\"position: relative; top: 0pt;\">\n";
    $buffer .= "<option selected value=\"\">[$_language[select_one]]</option>\n";

    if($this->communities->__hasItems()) {
      foreach($this->communities as $item) {
	$buffer .= "<option value=\"".$item->code."\">".$item->name."</option>\n";
      }
    }

    $buffer .= "</select>\n";
    $buffer .= "<input type=\"hidden\" name=\"list_action\" value=\"A_list_communities\">\n";
    $buffer .= "</font>\n";
    $buffer .= "<br><br><font class=\"textoint\">&raquo; $_language[projects_community]\n";
    $buffer .= "</td>\n";
    $buffer .= "</tr>\n";
    $buffer .= "</form>\n";
    $buffer .= "</table>\n";

    parent::add($buffer);

    return parent::__toString();

  }
}

?>
