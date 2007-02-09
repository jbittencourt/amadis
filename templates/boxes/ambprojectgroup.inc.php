<?php
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */


class AMBProjectGroup extends AMAbstractBox {

  public $group;
  public $proj; 

  public function __construct(AMProject $proj) {
    $this->requires("projectjoin.js");

    parent::__construct("ProjectGroupList");
    $this->proj = $proj;
    $this->group = $proj->getGroup(); 

    AMMain::addXOADHandler('AMBProjectGroupAction', 'AMBProjectGroup');
    
  }

  public function __toString() {
    global $_language,$_CMAPP;

    parent::add("<b>$_language[project_group]</b></font><br>");

    parent::add('<div id="projectGroupList">');
    parent::add('</div>');

    parent::add("<a href='".$_CMAPP['services_url']."/projects/members.php?frm_codProjeto=".$this->proj->codeProject."'");
    parent::add(" class='green'>$_language[more_members]</a><br><br>");

    $js = 'loadProjectGroup("'.$this->proj->codeGroup.'");';

    parent::add(CMHTMLObj::addScript($js));

    return parent::__toString();
  }
}