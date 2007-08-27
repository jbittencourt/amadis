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

  public function __construct() 
  {
  	global $_CMAPP;
  	parent::__construct($_CMAPP['imlang_url']."/box_acoes_rapidas.gif",self::COLOR_BOX_BLUE);
  }

  public function doAction() 
  {
  	global $_CMAPP,$_language;

  	if(isset($_REQUEST['frm_codeUser']) && ($_REQUEST['frm_codeUser'] == $_SESSION['user']->codeUser))
  	header("Location: $_CMAPP[services_url]/webfolio/webfolio.php");

  	if(!isset($_REQUEST['frm_codeUser'])) {
  		$urlpage = $_CMAPP['services_url']."/pages/viewpage.php?frm_page=users/user_".$_SESSION['user']->codeUser."&frm_codeUser=".$_SESSION['user']->codeUser;
  		$urlmyalbum = $_CMAPP['services_url']."/album/album.php";
  		$urlviewmsg = $_CMAPP['services_url']."/webfolio/scraps.php";
  		$urlpublish = $_CMAPP['services_url']."/upload/upload.php?frm_upload_type=user";

  		parent::add(self::getPageButton($urlpage));
  		parent::add(self::getBlogButton($_CMAPP['services_url'].'/blog/blog.php'));
  		parent::add(self::getAlbumButton($urlmyalbum));
		
  		parent::add('<br /><br />');
  		parent::add(self::getMessageButton($urlviewmsg));
  		parent::add(self::getWikiButton($_SESSION['user']->codeUser));

  		parent::add('<br /><br />');
  		parent::add('<span style="font-weight: bold;">'.$_language['other_actions'].':</span><br />');
  		parent::add("<a href='$urlpublish' class ='cinza'>&raquo; ".$_language['publish_my_home_page']."</a><br />");
  		
  		
  	} else {

  		$urlpage = $_CMAPP['services_url']."/pages/viewpage.php?frm_page=users/user_$_REQUEST[frm_codeUser]&frm_codeUser=$_REQUEST[frm_codeUser]";
  		$urlfriend = $_SERVER['PHP_SELF']."?frm_codeUser=$_REQUEST[frm_codeUser]&action=A_make_friend";
  		$urlemail = $_CMAPP['services_url']."/correio/message.php";
  		$urldiary = $_CMAPP['services_url']."/blog/blog.php?frm_codeUser=".$_REQUEST['frm_codeUser']."";
  		$urllibrary = $_CMAPP['services_url']."/library/library.php?frm_codeUser=".$_REQUEST['frm_codeUser']."";
  		$urlviewmsg = $_CMAPP['services_url']."/webfolio/scraps.php?frm_codeUser=".$_REQUEST['frm_codeUser']."";
  		$urlviewalbum = $_CMAPP['services_url']."/album/viewalbum.php?frm_codeUser=".$_REQUEST['frm_codeUser']."";
		
  		parent::add(self::getPageButton($urlpage));
  		parent::add(self::getBlogButton($urldiary));
  		parent::add(self::getFilesButton($urllibrary));
		parent::add('<br /><br />');
  		parent::add(self::getAlbumButton($urlviewalbum));
  		parent::add(self::getMessageButton($urlviewmsg));

  		if(!empty($_SESSION['user'])) {
  			if(!$_SESSION['user']->isMyFriend($_REQUEST['frm_codeUser'])) {
  				parent::add(self::getAddFriendButton('#'));
  				
  				$box = new AMColorBox($_languge['add_friend'], AMColorBox::COLOR_BOX_YELLOWB);
  				$box->add("<form method='post' action='$_SERVER[PHP_SELF]' name='add_friend'>");
  				$box->add("<font class=texto>$_language[send_a_comment]</font><br />");
  				$box->add("<textarea cols=27 rows=5 name='frm_comentary'></textarea><br />");
  				$box->add("<input type='hidden' name='frm_codeUser' value='$_REQUEST[frm_codeUser]'>");
  				$box->add("<input type='hidden' name='action' value='A_make_friend'>");
  				$box->add("<input type=submit value='$_language[send]'>");
  				$box->add("</form>");
  				parent::add('<div id="addFriend" style="display:none;">');
  				parent::add($box);
  				parent::add("</div>");
  			}

  			$sendMessageBox = new AMTShowHide("sendMessage", "&raquo; $_language[send_a_message]", AMTShowHide::HIDE);
  			$sendMessageBox->add("<form method='post' action='$_SERVER[PHP_SELF]' name='send_message'>");
  			$sendMessageBox->add("<font class=texto>$_language[message]</font><br />");
  			$sendMessageBox->add("<textarea cols='27' rows='5' name='frm_message'></textarea><br />");
  			$sendMessageBox->add("<input type='hidden' name='action' value='A_send_message'>");
  			$sendMessageBox->add("<input type='hidden' name='frm_codeUser' value='$_REQUEST[frm_codeUser]'>");
  			$sendMessageBox->add("<input type=submit value='$_language[send]'>");
  			$sendMessageBox->add("</form>");
  			parent::add('<br />');
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
  	public function addLink($link, $url) 
  	{
  		$this->links[] = array("url"=>$url,"link"=>$link);
  	}

  	public function __toString() 
  	{
  		return parent::__toString();
  	}
  
    /* STATIC METHODS, SPECIAL BUTTON */
  	public static function getPageButton($url)
  	{
    	global $_CMAPP,$_language;

    	$button  = '<button id="page" class="webfolio-items button-as-link" type="button" onclick="AM_openURL(\''.$url.'\')">'
    			 . '<img src="'.$_CMAPP['images_url'].'/dot.gif" height="25px;" width="10px" alt="" /><br />'
    			 . '<span class="webfolio-items-text"> '.$_language['see_home_page'].'</span>'
    			 . '</button>';
    	return $button;
  	}
  	  
  	public static function getBlogButton($url)
  	{
    	global $_CMAPP,$_language;

    	$button  = '<button id="blog" class="webfolio-items button-as-link" type="button" onclick="AM_openURL(\''.$url.'\')">'
    			 . '<img src="'.$_CMAPP['images_url'].'/dot.gif" height="25px;" width="10px" alt="" /><br />'
    			 . '<span class="webfolio-items-text"> '.$_language['see_blog'].'</span>'
    			 . '</button>';
    	return $button;
  	}

  	public static function getAddFriendButton($url)
  	{
    	global $_CMAPP,$_language;

    	$button  = '<button id="friend" class="webfolio-items button-as-link" type="button" onclick="$(\'addFriend\').toggle();">'
    			 . '<img src="'.$_CMAPP['images_url'].'/dot.gif" height="25px;" width="10px" alt="" /><br />'
    			 . '<span class="webfolio-items-text"> '.$_language['friend'].'</span>'
    			 . '</button>';
    	return $button;
  	}

  	public static function getFilesButton($url)
  	{
    	global $_CMAPP,$_language;

    	$button  = '<button id="files" class="webfolio-items button-as-link" type="button" onclick="AM_openURL(\''.$url.'\')">'
    			 . '<img src="'.$_CMAPP['images_url'].'/dot.gif" height="25px;" width="10px" alt="" /><br />'
    			 . '<span class="webfolio-items-text"> '.$_language['see_files'].'</span>'
    			 . '</button>';
    	return $button;
  	}
  	
  	public static function getAlbumButton($url)
  	{
    	global $_CMAPP,$_language;

    	$button  = '<button id="album" class="webfolio-items button-as-link" type="button" onclick="AM_openURL(\''.$url.'\')">'
    			 . '<img src="'.$_CMAPP['images_url'].'/dot.gif" height="25px;" width="10px" alt="" /><br />'
    			 . '<span class="webfolio-items-text"> '.$_language['see_album'].'</span>'
    			 . '</button>';
    	return $button;
  	}

  	public static function getMessageButton($url)
  	{
    	global $_CMAPP,$_language;
  		
    	/*
  		 * Check for new messages since last login.
  		 */
  		if(!isset($_REQUEST['frm_codeUser'])) {
    		$new_messages = $_SESSION['user']->checkForNewMessages();
  			$txt_link = ($new_messages > 0 ? "($new_messages)*" : '');
  		}    	
    	
    	$button  = '<button id="message" class="webfolio-items button-as-link" type="button" onclick="AM_openURL(\''.$url.'\')">'
    			 . '<img src="'.$_CMAPP['images_url'].'/dot.gif" height="25px;" width="10px" alt="" /><br />'
    			 . '<span class="webfolio-items-text"> '.$_language['read_messages'].$txt_link.'</span>'
    			 . '</button>';
    	return $button;
  	}
  	
  	public static function getWikiButton($user)
  	{
    	global $_CMAPP,$_language;
    
    	$urlwiki = $_CMAPP['services_url']."/wiki/index.php?frm_namespace=user_".$user;
    	$button  = "<button id='wiki' class='webfolio-items button-as-link' type='button' onclick=\"AM_openURL('$urlwiki')\">";
    	$button .= "<img src='$_CMAPP[images_url]/dot.gif' height='25px;'><br />";
    	$button .= "<span class='webfolio-items-text'>Wiki</span>";
    	$button .= "</button>";
    	return $button;
  	}
  	
}