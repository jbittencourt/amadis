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

    $formAction = $_CMAPP['form_login_action'];
    parent::add("<div id=\"loginInit\">");
	parent::add("				<div id=\"loginInitTopBorder\"></div>");
	parent::add("				<div id=\"loginInitContent\">"); 
	parent::add("				<ul>");
	parent::add("					<li id=\"loginInitContentImage\">Login:</li>");
	parent::add("					<form action=\"".$formAction."\" method=\"post\" name=\"loginbox\">");
	parent::add("					<input type=\"hidden\" name=\"login_action\" value=\"A_login\">");
    parent::add("					<li >".$_language['user'].":</li>");
	parent::add("					<li > <input type=\"text\" name=\"frm_username\" size=\"13\"></li>");
	parent::add("					<li >".$_language['password'].":</li>");
	parent::add("					<li ><input type=\"password\" name=\"frm_password\" size=\"13\"</li>");
	parent::add("					<li id=\"loginExit\">");
	parent::add("						<button id=\"logout_button\" type=\"submit\"><div id=\"loginInitLeftImage\">&nbsp;&nbsp;</div><div id=\"loginInitLog\">$_language[log]</div><div id=\"loginInitRightImage\">&nbsp;&nbsp;</div>	</button>");
	parent::add("					</li>");
	parent::add("				</ul>");	
	parent::add("			</div>");
	parent::add("			<div id=\"loginInitBottomBorder\"></div>");
	
	 if(!empty($this->requestVars)) {
      foreach($this->requestVars as $k=>$item) {
		parent::add("<input type=\"hidden\" name=\"$k\" value=\"$item\">");
      }
    }
    
    parent::add("</form>");
	parent::add("</div>");
    
   
 

 
    return parent::__toString();
  }

}


?>