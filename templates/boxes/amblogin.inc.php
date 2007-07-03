<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */


//Changed Extends from AMMenuBox to CMHtmlObj
class AMBLogin extends CMHtmlObj {

  private $requestVars = array();

  public function __construct() {
    parent::__construct();
    //self::requires("login.css",CMHTMLObj::MEDIA_CSS);

    
    //variables of the request that should not be propagated to the
    //next request
    $erase_vars[] = 'AMADIS';
    $erase_vars[] = 'frm_username';
    $erase_vars[] = 'frm_password';
    $erase_vars[] = 'frm_ammessage';
    $erase_vars[] = 'frm_amerror';
    $erase_vars[] = 'frm_amalert';

    //the login box propagate the request vars to the login
    //so the user doesn't need can remain visiting the current
    //page withou lost the context. If only test for variables that should
    //the propagate in the $erase_vars array
    foreach($_REQUEST as $k=>$item) {
      if(!in_array($k,$erase_vars)) {
	$this->requestVars[$k] = $item;
      }
    }
  }

  function __toString() {
    global $_CMAPP, $_language;

    $injection = array(
    	'formAction'=>$_CMAPP['form_login_action'],
    	'label_user'=>$_language['user'],
    	'label_password'=>$_language['password'],
    	'closures'=>array()
    );
    
    if(!empty($this->requestVars)) {
      foreach($this->requestVars as $k=>$item) {
		$injection['closures'][] = '<input type="hidden" name="'.$k.'" value="'.$item.'" />';
      }
    }
    
    
    parent::add(AMHTMLPage::loadView($injection, 'login_box', 'core_templates'));
    
    return parent::__toString();
  }

}
?>