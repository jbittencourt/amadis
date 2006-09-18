<?
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBProjectJoin extends AMColorBox {

  protected $proj;

  public function __construct(AMProject $proj) {
    global $_CMAPP;
    $this->proj = $proj; 
    parent::__construct($_CMAPP['imlang_url']."/box_participar_projeto.gif",self::COLOR_BOX_GREEN);

    $this->requires("projectjoin.js",self::MEDIA_JS);

	AMMain::addXOADHandler('AMBProjectJoinAction', 'AMBProjectJoin');
      
  }


  public function __toString() {
    global $_CMAPP,$_language;
    $group = $this->proj->getGroup();

    parent::add("<div id='project_join'>");
    if(!$group->hasRequestedJoin($_SESSION['user']->codeUser)) {
      $box = new AMTShowHide("sendMessage", "$_language[project_join]", AMTShowHide::HIDE);
      $box->add("<form method=\"post\" action=\"$_SERVER[PHP_SELF]\" name=\"form_project_join\" id=\"form_project_join\">");
      $box->add("<font class=texto>$_language[project_join_request]</font><br>");
      $box->add("<textarea cols=27 rows=5 name=\"frm_text\"></textarea><br>");
      $box->add("<input type=\"hidden\" name=\"frm_codeProject\" value=\"".$this->proj->codeProject."\">");
      $box->add("<input type=button onClick='sendProjectJoin()' value=\"$_language[send]\">");
      $box->add("</form>");
      parent::add($box);
    }
    else {
      parent::add("<p align=center>$_language[request_join_waiting]</p>");
    }
    parent::add("</div>");


    return parent::__toString();
  }

}




?>