<?

/**
 * Box to join in the community
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Cristiano S. Basso <csbasso@lec.ufrgs.br>
 */

class AMBCommunityJoin extends AMColorBox implements CMActionListener {

  protected $community;

  public function __construct(AMCommunities $community) {
    global $_CMAPP;
    $this->community = $community; 
    parent::__construct($_CMAPP[imlang_url]."/img_participar_comunidade.gif",self::COLOR_BOX_BEGE);
  }

  public function doAction() {
    global $_CMAPP,$_language;

    if(empty($_REQUEST[join_action])) 
      return false;

    switch($_REQUEST[join_action]) {
    case "A_join":
      if(!empty($_REQUEST[frm_text])) {
	$group = $this->community->getGroup();
	try {
	  $group->userRequestJoin($_SESSION[user]->codeUser,$_REQUEST[frm_text]);
	}
	catch(CMDBException $e) {
	  $err = new AMError($_langauge[error_join_request],get_class($this));
	  return false;
	}
	$men = new AMMessage($_language[msg_join_request_send],get_class($this));
      }
      else {
	$mem = new AMError($_language[error_no_explain_join_community],get_class($this));
      }
      break;
    }
  }

  public function __toString() {
    global $_CMAPP,$_language;
    $group = $this->community->getGroup();

    
    if(!$group->hasRequestedJoin($_SESSION[user]->codeUser)) {
      $box = new AMTShowHide("sendMessage", "$_language[community_join]", AMTShowHide::HIDE);
      $box->add("<form method=\"post\" action=\"$_SERVER[PHP_SELF]\" name=\"send_message\">");
      $box->add("<font class=texto>$_language[project_join_request]</font><br>");
      $box->add("<textarea cols=27 rows=5 name=\"frm_text\"></textarea><br>");
      $box->add("<input type=\"hidden\" name=\"join_action\" value=\"A_join\">");
      $box->add("<input type=\"hidden\" name=\"frm_codeCommunity\" value=\"".$this->community->code."\">");
      $box->add("<input type=submit value=\"$_language[send]\">");
      $box->add("</form>");
      parent::add($box);
    }
    else {
      parent::add("<p align=center>$_language[request_join_waiting]</p>");
    }

    return parent::__toString();
  }

}



?>