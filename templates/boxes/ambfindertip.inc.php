<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBFinderTip extends CMHTMLObj {
  
  private $user, $message;

  public function __construct(AMUser $user, AMFinderMessages $message) {
    parent::__construct();
    
    $this->user = $user;
    $this->codeRequest = $message->code;
    $this->message = (strlen($message->message) < 50? $message->message: substr($message->message, 0, 50)."...");

  }

  public function __toString() {
    global $_CMAPP, $_language;

    parent::add("<div id=\"tipms\">");
    parent::add("<div id=\"tipmscorpo\">");
    //user image
    $userThumb = new AMFinderTipThumb();
    $userThumb->codeArquivo = ($this->user->foto==0 ? AMUser::DEFAULT_FOTO : $this->user->foto);
    try {
      $userThumb->load();
      $userThumbURL = $userThumb->thumb->getThumbURL();
      parent::add("<img class=\"tipfoto\" src=\"$userThumbURL\">");
    }catch (CMDBException $e) {
      parent::add("$_language[error_loading_image]");
    }
    //button exit
    $onClick = "onClick=\"Finder_removeAlert('finderAlert_".$_SESSION['user']->codeUser."_".$this->user->codeUser."');\"";

    parent::add("<img class=\"tipsair\" src=\"$_CMAPP[images_url]/tipms_box_btsair.png\" $onClick>");
    
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"60\" border=\"0\"><br>");

    //message
    parent::add("<div id=\"tipfala\">$this->message</div>");
    
    //reply to
    $sessionId = $_SESSION['user']->codeUser."_".$this->user->codeUser;
    $onClick  = "onclick=\"Finder_openChatWindow('$sessionId');";
    $onClick .= " Finder_removeAlert('finderAlert_$sessionId');\"";

    parent::add("<img class=\"tipbtmsg\" src=\"$_CMAPP[images_url]/tipms_box_btmsg.png\" $onClick>");
    parent::add("<div id=\"tipresposta\">$_language[reply_to] <br>");
    
    parent::add($this->user->username."</div>");
    parent::add("<div id=\"tipfooter\"><img src=\"$_CMAPP[images_url]/tipms_box_footer.png\"></div></div>");
    parent::add("</div>");
    
    return parent::__toString();
  
  }
}
?>