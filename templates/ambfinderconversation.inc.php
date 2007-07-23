<?
class AMBFinderConversation extends CMHTMLObj {
  
  protected $user, $sessionId;

  public function __construct(AMUser $user, $sessionId) {

    $this->user = $user;
    $this->sessionId = $sessionId;

    parent::__construct();
  }

  public function __toString() {
    global $_CMAPP, $_language;

    parent::add(CMHTMLObj::getScript("var AMFinder_".$this->sessionId." = ".XOAD_Client::register(new AMFinder)));
    parent::add(CMHTMLObj::getScript("var AMFinder_Timeout_".$this->sessionId." = window.setInterval(\"Finder_getNewMessages('".$this->sessionId."');\", 5000);"));
    parent::add("<div id='chatcorpo'> ");
    parent::add("  <div id='area_mensagens' class='sobre'> ");
    parent::add("    <div id='areatextochat'>");

    //box list messages
    parent::add("<iframe class='chat' id='iChat_{$this->sessionId}' name='chat' src='$_CMAPP[media_url]/dcom.htm'></iframe>");
    //parent::add("<div class='chat' id='chat_".$this->sessionId."' name='chat'></div>");
    
    parent::add("    </div>");
    parent::add("    <div id='seta1' class='posicaoseta'><img src='$_CMAPP[images_url]/box_msg_areachat_01.png' width='14' height='10' border='0'></div>");
    parent::add("    <div id='seta2' class='posicaoseta'><img src='$_CMAPP[images_url]/box_msg_areachat_02.png' width='14' height='10' border='0'></div>");
    parent::add("  </div>");
    parent::add("  <div id='area_informacao' class='sobre'><span id='traco'></span> ");
    parent::add("    <div id='areainfocorpo'><img src='$_CMAPP[images_url]/box_msg_pecas.png' width='79' height='121' border='0'></div>");
    parent::add("<div id='infoelement'>");

    //add a user recipient thumbnail
    $userThumb = new AMUserThumb;
    try {
    	$userThumb->codeFile = $this->user->picture;
    
    	try {
      		$userThumb->load();
      		$userThumbURL = $userThumb->thumb->getThumbURL();
    	}catch (CMDBException $e) {
      		parent::add("$_language[error_loading_image]");
    	}
    }catch(CMObjEPropertieValueNotValid $e) {
    	$userThumb = new AMUserThumb(AMUserPicture::DEFAULT_IMAGE);
    	$userThumb->load();
    	$userThumbURL = $userThumb->thumb->getThumbURL();
    }
    parent::add("<img src='$userThumbURL' class='element'><br />");
    parent::add($this->user->username."<br />");

    parent::add("<img class='setas' src='$_CMAPP[images_url]/box_msg_setas.png'><br />");

    //add a user sender thumbnail
    $userThumb = new AMUserThumb;
    
    try {
    	$userThumb->codeFile = $_SESSION['user']->picture;

    	try {
      		$userThumb->load();
      		$userThumbURL = $userThumb->thumb->getThumbURL();
    	}catch (CMDBException $e) {
      		parent::add("$_language[error_loading_image]");
    	}
    }catch(CMObjEPropertieValueNotValid $e) {
    	$userThumb = new AMUserThumb(AMUserPicture::DEFAULT_IMAGE);
    	$userThumb->load();
    	$userThumbURL = $userThumb->thumb->getThumbURL();
    }
    parent::add("<img src='$userThumbURL' class='element'><br />");
    parent::add($_SESSION['user']->username."<br />");

    parent::add("    </div>");
    parent::add("<div id='footerlement'><img src='$_CMAPP[images_url]/box_msg_pecas2.png'></div>");
    parent::add("  </div>");
    parent::add("  <div id='area_enviarmsg'>");

    $style = "style='border: 0px; width: 99%; height: 110px;' overflow:hidden;";
    parent::add("<iframe src='$_CMAPP[services_url]/finder/sendbox.php?frm_codeUser=".$this->user->codeUser."' $style></iframe>");

    parent::add("    <div id='seta3' class='posicaoseta'><img src='$_CMAPP[images_url]/box_msg_areaenvio_03.png'></div>");
    parent::add("    <div id='seta4' class='posicaoseta'><img src='$_CMAPP[images_url]/box_msg_areaenvio_04.png'></div>");
    parent::add("  </div>");

    parent::add("</div>");

    return parent::__toString();
  }
}

?>