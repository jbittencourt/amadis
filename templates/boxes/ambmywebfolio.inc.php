<?
class AMBMyWebfolio extends AMColorBox implements CMActionListener {
  
  private $link = array();
    
  public function __construct() {
    global $_CMAPP;
    parent::__construct($_CMAPP['imlang_url']."/box_acoes_rapidas.gif",self::COLOR_BOX_BLUE);
  }
    
  public function doAction() {
    global $_CMAPP,$_language;
    
    if(isset($_REQUEST['frm_codeUser']) && ($_REQUEST['frm_codeUser'] == $_SESSION['user']->codeUser))
      header("Location: $_CMAPP[services_url]/webfolio/webfolio.php");
    
    if(!isset($_REQUEST['frm_codeUser'])) {
      $urlpage = $_CMAPP['services_url']."/pages/viewpage.php?frm_page=users/user_".$_SESSION['user']->codeUser."&frm_codeUser=".$_SESSION['user']->codeUser;
      $urlpublish = $_CMAPP['services_url']."/upload/upload.php?frm_upload_type=user";
      $urlviewmsg = $_CMAPP['services_url']."/webfolio/messages.php";
      
      parent::add("<a href=\"$urlpage\" class =\"cinza\">&raquo; ".$_language['see_my_home_page']."</a><br>");
      parent::add("<a href=\"$urlpublish\" class =\"cinza\">&raquo; ".$_language['publish_my_home_page']."</a><br>");
      //parent::add("<a href=\"$urlviewmsg\" class =\"cinza\">&raquo; ".$_language['read_my_messages']."</a><br>");
      
    }else {
      $urlpage = $_CMAPP['services_url']."/pages/viewpage.php?frm_page=users/user_$_REQUEST[frm_codeUser]&frm_codeUser=$_REQUEST[frm_codeUser]";
      $urlfriend = $_SERVER['PHP_SELF']."?frm_codeUser=$_REQUEST[frm_codeUser]&action=A_make_friend";
      $urlemail = $_CMAPP['services_url']."/correio/message.php";
      $urldiary = $_CMAPP['services_url'].'/diario/diario.php?frm_codeUser='.$_REQUEST['frm_codeUser'];

      parent::add("<a href=\"$urlpage\" class =\"cinza\">&raquo; ".$_language['see_home_page']."</a><br>");
      parent::add("<a href=\"$urldiary\" class =\"cinza\">&raquo; ".$_language['see_diary']."</a><br>");

      if(!empty($_SESSION['user'])) {
	parent::add("<a href=\"$urlemail\" class=\"cinza\">&raquo; ".$_language['send_email']."</a><br>");
	
	if(!$_SESSION['user']->isMyFriend($_REQUEST['frm_codeUser'])) {
	  $addFriendBox = new AMTShowHide("addFriend", "&raquo; $_language[add_friend]", AMTShowHide::HIDE);
	  $addFriendBox->add("<form method=\"post\" action=\"$_SERVER[PHP_SELF]\" name=\"add_friend\">");
	  $addFriendBox->add("<font class=texto>$_language[send_a_comment]</font><br>");
	  $addFriendBox->add("<textarea cols=27 rows=5 name=\"frm_comentary\"></textarea><br>");
	  $addFriendBox->add("<input type=\"hidden\" name=\"frm_codeUser\" value=\"$_REQUEST[frm_codeUser]\">");
	  $addFriendBox->add("<input type=\"hidden\" name=\"action\" value=\"A_make_friend\">");
	  $addFriendBox->add("<input type=submit value=\"$_language[send]\">");
	  $addFriendBox->add("</form>");
	  parent::add($addFriendBox);
	}
	
	$addFriendBox = new AMTShowHide("sendMessage", "&raquo; $_language[send_a_message]", AMTShowHide::HIDE);
	$addFriendBox->add("<form method=\"post\" action=\"$_SERVER[PHP_SELF]\" name=\"send_message\">");
	$addFriendBox->add("<font class=texto>$_language[message]</font><br>");
	$addFriendBox->add("<textarea cols=27 rows=5 name=\"frm_message\"></textarea><br>");
	$addFriendBox->add("<input type=\"hidden\" name=\"action\" value=\"A_send_message\">");
	$addFriendBox->add("<input type=\"hidden\" name=\"frm_codeUser\" value=\"$_REQUEST[frm_codeUser]\">");
	$addFriendBox->add("<input type=submit value=\"$_language[send]\">");
	$addFriendBox->add("</form>");
	//parent::add($addFriendBox);
      }
    }
  }
  
  public function addLink($link, $url) {
    $this->links[] = array("url"=>$url,"link"=>$link);
  }

  public function __toString() {
    
    return parent::__toString();
      
  }

}

?>