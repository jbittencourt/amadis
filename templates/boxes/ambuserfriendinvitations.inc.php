<?php
/**
 * Invitations friends box
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @category AMCategoria
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 * @see AMColorBox, CMActionListener
 */
class AMBUserFriendInvitations extends AMColorBox implements CMActionListener {

	protected $invitations, $__hasItems;

	public function __construct() {
		global $_CMAPP;
		parent::__construct("",self::COLOR_BOX_BEGE);		
		try {
			$this->invitations = $_SESSION['user']->listFriendsInvitations();
			($this->invitations->__hasItems() ? $this->__hasItems = true : $this->__hasItems = false);			
		}catch(AMWEFirstLogin $e) {
			$this->__hasItems = false;			
		}
	}

  /**
   * Check is have new invitations
   *
   * @access public
   * @param Void
   * @return Boolean AMBUserFriendInvitations::__hasItems;
   */
  public function __hasInvitations() {
  	return $this->__hasItems;
  }


  public function doAction() {
  	global $_CMAPP,$_language;

  	if(!isset($_REQUEST['invfriend_action'])) {
  		return false;
  	}


  	switch($_REQUEST['inv_action']) {
  		case "A_accpect":
  			//add the user to the project
  			try {
  				$proj->addMember($_SESSION['user']->codeUser);
  			}
  			catch(CMDBException $e) {
  				$err = new AMError($_language['error_joining_project'],get_class($this));
  				return false;
  			}

  			try {
  				$inv->status = AMProjectMemberJoin::ENUM_STATUS_ACCEPTED;
  				$inv->save();
  			}
  			catch(CMDBException $e) {
  				//if occur some problem, remove the user
  				$proj->removeMember($_SESSION['user']->codeUser);
  				$err = new AMError($_language['error_joining_project'],get_class($this));
  				return false;
  			}

  			$msg = new AMMessage($_language['msg_joined_project']." $proj->title.",get_class($this));
  			break;
  		case "A_reject":
  			try {
  				$inv->status = AMProjectMemberJoin::ENUM_STATUS_REJECTED;
  				$inv->save();
  			}
  			catch(CMDBException $e) {
  				//if occur some problem, remove the user
  				$err = new AMError($_language['error_joining_project'],get_class($this));
  				return false;
  			}
  			$msg = new AMMessage($_language['msg_rejected_project']." $proj->title.",get_class($this));
  			break;
  	}

  }

  public function __toString() {
  	global $_language,$_CMAPP;

  	if($this->__hasItems) {

  		$_first = true;

  		$this->requires("alertbox.css", CMHTMLObj::MEDIA_CSS);
  		parent::addPageBegin(CMHTMLObj::getScript("EnvSession_numFriendsInvitation = ".$this->invitations->count().";"));
  		foreach($this->invitations as $friend) {

  			$user = $friend->invitation[0];

  			parent::add("<div id='$friend->codeUser'>");
  			parent::add("<table border=0 cellspacing=1 cellpadding=2 width=\"100%\">");

  			//user image thumbnail
  			parent::add("<tr><td>");
  			$thumb = new AMUserThumb;
  			$f  = $friend->picture;
  			if(empty($f)) $f = AMUserFoto::DEFAULT_IMAGE;
  			$thumb->codeFile = $f;
  			try {
  				$thumb->load();
  				parent::add($thumb->getView());
  			}
  			catch(CMDBException $e) {
  				echo $e; die();
  			}

  			//an empty column
  			parent::add("</td><td><img src=\"$_CMAPP[images_url]/dot.gif\" width=10>");

  			//invitation text
  			parent::add("</td><td class=\"texto\">");
  			parent::add("<span class=\"cinza\">$user->comentary</span><br>");
  			parent::add("$_language[friend_invitation] ");
  			parent::add("<a class=\"blue\" href=\"$_CMAPP[services_url]/webfolio/userinfo_details.php?frm_codeUser=$friend->codeUser\">$friend->username</a>.");
  			parent::add("</td><td align=center>");

  			$time = $friend->time;
  			$link = $_CMAPP['services_url']."/webfolio/userinfo_details.php?frm_codeUser=".$friend->codeUser;

  			$mkFriend = "AMEnvSession.makeFriend($friend->codeUser, $time, '', '$_language[msg_invitation_user_success]', '$_language[error_invitation_user_failed]', AMEnvSessionCallBack.onMakeFriend);";
  			$rjFriend = "AMEnvSession.rejectFriend($friend->codeUser, $time, '','', AMEnvSessionCallBack.onRejectFriend);";


  			parent::add("<a class='blue cursor' onClick=\"$mkFriend\">$_language[add_friend]</a><br>");
  			parent::add("<a class='blue cursor' onClick=\"$rjFriend\">$_language[not_add_friend]</a><br>");

  			if($_first && $this->invitations->count() > 1) {
  				parent::add("<tr><td colspan=4><br>");
  				parent::add(new AMDotline("100%"));
  			}

  			parent::add("</tr>");
  			$_first = false;
  			parent::add("</table>");
  			parent::add("</div>");
  		}

  		return parent::__toString();
  	}
  }
}