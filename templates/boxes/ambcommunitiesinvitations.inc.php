<?

/**
 * Communities user invitation
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 */

class AMBCommunitiesInvitations extends AMColorBox implements CMActionListener {
    
  protected $invitations, $__hasItems;

  public function __construct() {
    global $_CMAPP;

    parent::__construct("",self::COLOR_BOX_BEGE);

    try {
      $this->invitations = $_SESSION['user']->listCommunitiesInvitations();
      ($this->invitations->__hasItems() ? $this->__hasItems = true : $this->__hasItems = false);
    }catch(AMWEFirstLogin $e) {
      $this->__hasItems = false;
    }
  }

  public function __hasInvitations() {
    return $this->__hasItems;
  }


  public function doAction() {
    global $_CMAPP,$_language;
    
    if(!isset($_REQUEST['inv_com_action'])) {
      return false;
    }
    
    try {
      $co = new AMCommunities;
      $co->code = $_REQUEST['inv_codeCommunity'];
      $co->load();             
      $group = $co->getGroup();
    }
    catch(CMDBNoRecord $e) {
      $err = new AMError($_language['error_joining_community'],get_class($this));
      return false;
    }
    catch(AMException $e){
      $err = new AMError($_language['error_joining_community'],get_class($this));
      return false;
    }


    switch($_REQUEST['inv_com_action']) {
    case "A_accept":
      try {
	$group->acceptInvitation($_REQUEST['inv_codeGroupMemberJoin'], "");
	unset($_SESSION['amadis']['communities']);
      }
      catch(CMGroupException $e) {
	$err = new AMError($_language['error_joining_community'],get_class($this));
	return false;
      }

      $msg = new AMMessage($_language['msg_joined_community']." $co->name.",get_class($this));
      break;

    case "A_reject":
      try {
	$group->rejectInvitation($_REQUEST['inv_codeGroupMemberJoin'], "");
      }
      catch(CMGroupException $e) {
	$err = new AMError($_language['error_joining_community'],get_class($this));
	return false;
      }
      $msg = new AMMessage($_language['msg_rejected_community']." $co->name.",get_class($this));
      break;
    }

  }

  public function __toString() {
    global $_language,$_CMAPP;

    if($this->__hasItems) {
      parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=20 height=20>");
      parent::add("<table border=0 cellspacing=1 cellpadding=2 width=\"100%\">");
      $_first = true;
      foreach($this->invitations as $co) {
	if(!$_first) {
	  parent::add("<tr><td colspan=4>");
	  parent::add(new AMDotline("100%"));
	}
	
	//project image thumbnail
	parent::add("<tr><td>");
	$thumb = new AMCommunityThumb;
	$thumb->codeArquivo = ($co->image==0 ? 3 : $co->image);
	try {
	  $thumb->load();
	} catch(CMDBNoRecord $e) {}

	parent::add($thumb->getView());
	
	//an empty column
	parent::add("</td><td><img src=\"$_CMAPP[images_url]/dot.gif\" width=10>");

	//invitation text
	parent::add("</td><td class=\"texto\">");
	parent::add($_language['community_invitation'].' ');
	parent::add("<a class=\"blue\" href=\"$_CMAPP[services_url]/communities/community.php?frm_codeCommunity=$co->code\">$co->name</a>.");
	parent::add("</td><td align=center>");

	$inv = $co->invitation[0];
	$link = $_CMAPP['services_url']."/webfolio/webfolio.php?inv_codeCommunity=$co->code&inv_codeGroupMemberJoin=".$inv->codeGroupMemberJoin;

	parent::add("<a href=\"$link&inv_com_action=A_accept\" class=\"blue\">$_language[accept]</a><br>");
	parent::add("<a href=\"$link&inv_com_action=A_reject\"class=\"blue\">$_language[reject]</a>");
	parent::add("</tr>");
	$_first = false;
      }
      parent::add("</table>");

      return parent::__toString();
    }
  }
}

?>
