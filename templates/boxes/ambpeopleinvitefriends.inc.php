<?

class AMBPeopleInviteFriends extends CMHtmlObj implements CMActionListener {

  public function __construct() {
    parent::__construct();

  }
  
  public function doAction() {
    global $_language;

    switch($_REQUEST[friend_action]) {
    default:
      
      parent::add("<form action=$_SERVER[PHP_SELF]?friend_action=A_invite method=post name=form_invite>");
      
      $list = $_SESSION[environment]->listNotMyFriendsUsers();
      if($list->__hasItems()) {
	foreach($list as $item) {
	  $i++;
	  
	  parent::add("<input type=checkbox name=user_$i value=$item->codeUser>");
	  parent::add(new AMTUserInfo($item));
	  parent::add("<br>");
	  
	}
	parent::add("<input type=submit value=$_language[add_friends]>");
	
	parent::add("</form>");
      }else parent::add("nao tem nada");
      
      break;
      
    case "A_invite":
      /**
       *Adiciona um amigo
       */
      $friend = new AMFriend;
      $friend->loadDataFromRequest();
      $friend->codeUser = $_SESSION[user]->codeUser;
      $friend->time = time();
      try{
	$friend->save();
	header("Location:$_SERVER[PHP_SELF]?amerror=invitation_user_success");
      }catch(CMException $e) {
	header("Location:$_SERVER[PHP_SELF]?amerror=invitation_user_failed");
      }
      break;

    case "invitUser":
      
      break;
    case AMFriend::ENUM_STATUS_ACCEPTED:
    
      break;
    case AMFriend::ENUM_STATUS_REJECTED:
    }
  }

  public function __toString() {
    
    return parent::__toString();
  
  }
}

?>