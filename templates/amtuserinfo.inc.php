<?
include("cminterface/widgets/cmwtip.inc.php");

/**
 * Return an link to the user info window.
 *
 * Return an link to the user info window. By default,
 * uses the user real name as link text, but an alternative
 * text can be used, passing an argument to the constructor.
 *
 * @package AMADIS
 * @subpackage AMTemplates
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 **/
class AMTUserInfo extends CMHTMLObj {

  const LIST_USERNAME=0;
  const LIST_FULLNAME=1;

  static protected $_inicialized = false;

  protected $user, $text;
  protected $username;
  protected $class;
  private $tip;
  
  public function __construct(CMUser $user,$username=self::LIST_FULLNAME,$setLink=false) {
    global $_CMAPP;
    parent::__construct();
    $this->user = $user;

    switch($username) {
    case self::LIST_FULLNAME:
      $this->text = $user->name;
      break;
    case self::LIST_USERNAME:
      $this->text = $user->username;
      break;
    }
    $this->class = "blue";
    
    
    $this->tip = new CMWTip($this->text,"");

    if($setLink){
      $this->tip->setLink($_CMAPP['services_url']."/webfolio/userinfo_details.php?frm_codeUser=$user->codeUser");
    }
    else{
      $this->tip->setLink("#");
    }


    $this->requires("userinfo.js",self::MEDIA_JS);

  }

  public function setClass($value) {
    $this->class = $value;
  }

  public function __toString() {
    global $_CMAPP, $_language;

    if(!self::$_inicialized) {
      self::$_inicialized = true;

      AMMain::addCommunicatorHandler('AMTUserinfoRender');

      $men = "<span class=\"text\">$_language[loading_user_info]</span>";
      parent::addPageBegin(self::getScript("var AMTUserinfoRender = new amtuserinforender(AMTUserinfoRenderCallBack);"));
      parent::addPageEnd(CMHTMLObj::getScript("userinfo_url = '$_CMAPP[services_url]/webfolio/userinfo.php?frm_codeUser='; loading_message = '$men';"));
    }

    $u = $this->user;
    $this->tip->setClass($this->class);
    $this->tip->setDivClass("userToolTip");
    $this->tip->setJSWrapperFunction("'amuserinfo($u->codeUser)'");
    $this->tip->setBehavior(CMWTip::BEHAVIOR_CLICK_AND_STAY);
    parent::add($this->tip);
  

    return parent::__toString();
			     
  }
}


?>
