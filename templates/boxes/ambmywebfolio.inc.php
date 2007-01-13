<?php
/**
 * Box de acoes do Webfolio
 * @package AMADIS
 * @subpackage AMBoxes
 * @access public
 */
class AMBMyWebfolio extends AMColorBox implements CMActionListener {

  /**
   * @var array link - List of the links to webfolio box.
   */
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
  		//  $urlviewmsg = $_CMAPP['services_url']."/webfolio/messages.php";
  		$urlmyalbum = $_CMAPP['services_url']."/album/album.php";
  		$urlviewmsg = $_CMAPP['services_url']."/webfolio/scraps.php";


  		parent::add("<a href='$urlpage' class ='cinza'>&raquo; ".$_language['see_my_home_page']."</a><br>");
  		parent::add("<a href='$urlpublish' class ='cinza'>&raquo; ".$_language['publish_my_home_page']."</a><br>");
  		parent::add("<a href='$urlmyalbum' class ='cinza'>&raquo; ".$_language['see_my_album']."</a><br>");
  		
  		/*
  		 * Check for new messages since last login.
  		 */
  		$new_messages = $_SESSION['user']->checkForNewMessages();
  		$txt_link = ($new_messages > 0 ? "($new_messages) Novas mensagens." : '');
  		parent::add("<a href='$urlviewmsg' class ='cinza'>&raquo; ".$_language['read_my_messages']."<br> $txt_link</a><br>");


  	}else {

  		$urlpage = $_CMAPP['services_url']."/pages/viewpage.php?frm_page=users/user_$_REQUEST[frm_codeUser]&frm_codeUser=$_REQUEST[frm_codeUser]";
  		$urlfriend = $_SERVER['PHP_SELF']."?frm_codeUser=$_REQUEST[frm_codeUser]&action=A_make_friend";
  		$urlemail = $_CMAPP['services_url']."/correio/message.php";
  		$urldiary = $_CMAPP['services_url']."/blog/blog.php?frm_codeUser=".$_REQUEST['frm_codeUser']."";
  		$urllibrary = $_CMAPP['services_url']."/library/library.php?frm_codeUser=".$_REQUEST['frm_codeUser']."";
  		$urlviewmsg = $_CMAPP['services_url']."/webfolio/scraps.php?frm_codeUser=".$_REQUEST['frm_codeUser']."";
  		$urlviewalbum = $_CMAPP['services_url']."/album/viewalbum.php?frm_codeUser=".$_REQUEST['frm_codeUser']."";

  		parent::add("<a href='$urlpage' class ='cinza'>&raquo; ".$_language['see_home_page']."</a><br>");
  		parent::add("<a href='$urldiary' class ='cinza'>&raquo; ".$_language['see_blog']."</a><br>");
  		parent::add("<a href='$urllibrary' class ='cinza'>&raquo; ".$_language['see_library']."</a><br>");
  		parent::add("<a href='$urlviewalbum' class ='cinza'>&raquo; ".$_language['see_album']."</a><br>");
  		parent::add("<a href='$urlviewmsg' class ='cinza'>&raquo; ".$_language['read_messages']."</a><br>");

  		if(!empty($_SESSION['user'])) {
  			if(!$_SESSION['user']->isMyFriend($_REQUEST['frm_codeUser'])) {
  				$addFriendBox = new AMTShowHide("addFriend", "&raquo; $_language[add_friend]", AMTShowHide::HIDE);
  				$addFriendBox->add("<form method='post' action='$_SERVER[PHP_SELF]' name='add_friend'>");
  				$addFriendBox->add("<font class=texto>$_language[send_a_comment]</font><br>");
  				$addFriendBox->add("<textarea cols=27 rows=5 name='frm_comentary'></textarea><br>");
  				$addFriendBox->add("<input type='hidden' name='frm_codeUser' value='$_REQUEST[frm_codeUser]'>");
  				$addFriendBox->add("<input type='hidden' name='action' value='A_make_friend'>");
  				$addFriendBox->add("<input type=submit value='$_language[send]'>");
  				$addFriendBox->add("</form>");
  				parent::add($addFriendBox);
  			}

  			$sendMessageBox = new AMTShowHide("sendMessage", "&raquo; $_language[send_a_message]", AMTShowHide::HIDE);
  			$sendMessageBox->add("<form method='post' action='$_SERVER[PHP_SELF]' name='send_message'>");
  			$sendMessageBox->add("<font class=texto>$_language[message]</font><br>");
  			$sendMessageBox->add("<textarea cols='27' rows='5' name='frm_message'></textarea><br>");
  			$sendMessageBox->add("<input type='hidden' name='action' value='A_send_message'>");
  			$sendMessageBox->add("<input type='hidden' name='frm_codeUser' value='$_REQUEST[frm_codeUser]'>");
  			$sendMessageBox->add("<input type=submit value='$_language[send]'>");
  			$sendMessageBox->add("</form>");
  			parent::add($sendMessageBox);
  		}
  	}
  }

  /**
   * Add a new link to webfolio box
   *
   * @access public
   * @param string $link - text to an <a>
   * @param string $url - url address to a link
   * @return void
   */
  public function addLink($link, $url) {
  	$this->links[] = array("url"=>$url,"link"=>$link);
  }

  public function __toString() {

  	return parent::__toString();

  }

}