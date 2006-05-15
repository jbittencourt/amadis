<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */



class AMBLogin extends AMMenuBox {

  private $requestVars = array();

  public function __construct() {
    parent::__construct();
    self::requires("login.css",CMHTMLObj::MEDIA_CSS);
    foreach($_REQUEST as $k=>$item) {
      if($k != "AMADIS" && $k != "frm_username" && $k != "frm_password" && $k != "submit") {
	$this->requestVars[] = "<input type=\"hidden\" name=\"$k\" value=\"$item\">";
      }
    }
  }

  function __toString() {
    global $_CMAPP;

    
    $formAction = $_CMAPP['form_login_action'];

    parent::add("<div id=\"login\">");
    parent::add("<form action=\"$formAction\" method=\"post\" name=\"loginbox\">");

    parent::add("<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">");
    parent::add("  <tr>");
    parent::add("    <td colspan=2><img src=\"".$_CMAPP['imlang_url']."/mn_box_login.gif\" ></td>");
    parent::add("  </tr>");

    parent::add("  <tr>");
    parent::add("    <td>");
    parent::add("      <img src=\"".$_CMAPP['images_url']."/dot.gif\" width=\"18\" height=\"1\">");
    parent::add("    </td>");
    parent::add("    <td valign=\"top\"><img src=\"".$_CMAPP['imlang_url']."/img_usuario.gif\">");
    parent::add("       <br>");
    parent::add("       <input type=\"hidden\" name=\"login_action\" value=A_login>");

    parent::add("       <input type=\"text\" name=\"frm_username\" size=15>");
    parent::add("    </td>");
    parent::add("   </tr>");

    parent::add("  <tr>");
    parent::add("    <td>");
    parent::add("       <img src=\"".$_CMAPP['images_url']."/dot.gif\" width=\"18\" height=\"1\">");
    parent::add("    </td>");
    parent::add("    <td valign=\"top\"><img src=\"".$_CMAPP['imlang_url']."/img_senha.gif\">");
    parent::add("       <br>");
    parent::add("       <input type=\"password\" name=\"frm_password\" size=15> ");
    parent::add("    </td>");
    parent::add("   </tr>");
    parent::add("   <tr>");
    parent::add("     <td><img src=\"".$_CMAPP['images_url']."/dot.gif\"></td>");
    parent::add("     <td align=\"right\"><img src=\"".$_CMAPP['images_url']."/dot.gif\" width=\"10\" height=\"15\">");
    parent::add("   </tr>");
    
    parent::add("   <tr>");
    parent::add("     <td><img src=\"".$_CMAPP['images_url']."/dot.gif\"></td>");
    parent::add("     <td align=\"right\"><button type=\"submit\"><img src=\"".$_CMAPP['imlang_url']."/bt_ok.gif\" border=\"0\"></button>");
    parent::add("     </td>");
    parent::add("   </tr>");
    parent::add("</tr>");
    parent::add("</table></div>");

    if(!empty($this->requestVars)) {
      foreach($this->requestVars as $item) {
	parent::add($item);
      }
    }
    
    parent::add("</form>");
 
    return parent::__toString();
  }

}


?>