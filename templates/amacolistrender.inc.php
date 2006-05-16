<?
/**
 * AMACOListRender
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMACO
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see CMHTMLObj
 */
class AMACOListRender extends CMHTMLObj {


  const PROJECTS=0;
  const COMMUNITIES=1;
  const USERS=2;

  public function __construct() {
    $this->requires('aco.js');
    parent::__construct("");
  }

  public function __toString() {
    global $_CMAPP;

    $_language = $_CMAPP[i18n]->getTranslationArray("acos");

    AMMain::addCommunicatorHandler('AMACOListRenderAction');
    parent::add(CMHTMLObj::getScript("var AMACOList = new amacolistrenderaction(AMACOListRenderActionCallBack);"));

    $items = array( $_language[users] => "ACO_listUsers()" ,
	            $_language[projects]     => "ACO_listProjects()",
		    $_language[communities]  => "ACO_listCommunities()" );
    parent::add("<P>");
    foreach($items as $label=>$value) {
      parent::add("<BUTTON type=button onClick='$value'>");
      parent::add("$label");
      parent::add("</BUTTON>");
    }

    parent::add("<P> $_language[aco_search] <INPUT type=text id=aco_search_string>");
    parent::add(AMMain::getSearchButton('searchACOS()'));


    parent::add("<P><DIV id='aco_messages'>");
    parent::add("</DIV>");
    
    parent::add("<SELECT id=frm_acos name=frm_acos size=10>");
    parent::add("</SELECT>");


    return parent::__toString();
  }

}


?>