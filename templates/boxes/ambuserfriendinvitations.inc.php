<?

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
note($e);
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
      parent::add("<table border=0 cellspacing=1 cellpadding=2 width=\"100%\">");
      $_first = true;
    
      foreach($this->invitations as $friend) {
	if(!$_first) {
	  parent::add("<tr><td colspan=4>");
	  parent::add(new AMDotline("100%"));
	}
      
	//user image thumbnail
	parent::add("<tr><td>");
	$user = $friend->invitation[0];
	parent::add(new AMTThumb($user->foto));

	//an empty column
	parent::add("</td><td><img src=\"$_CMAPP[images_url]/dot.gif\" width=10>");

	//invitation text
	parent::add("</td><td class=\"texto\">");
	parent::add("<span class=\"cinza\">$friend->comentary</span><br>");
	parent::add("$_language[friend_invitation] ");
	parent::add("<a class=\"blue\" href=\"$_CMAPP[services_url]/webfolio/userinfo_details.php?frm_codeUser=$user->codeUser\">$user->username</a>.");
	parent::add("</td><td align=center>");

	$time = $friend->time;
	$link = $_CMAPP['services_url']."/webfolio/userinfo_details.php?frm_codeUser=".$user->codeUser;
      
	parent::add("<a href=\"$link&inv_time=$time&action=A_make_friend\" class=\"blue\">$_language[add_friend]</a><br>");
	parent::add("</tr>");
	$_first = false;
      }
      parent::add("</table>");

      return parent::__toString();
    }
  }

}

?>