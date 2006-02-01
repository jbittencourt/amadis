<?


class AMBProjectGroup extends AMAbstractBox {

  public $group;
  public $proj; 

  public function __construct(AMProjeto $proj) {
    $this->requires("projectjoin.js");

    parent::__construct("ProjectGroupList");
    $this->proj = $proj;
    $this->group = $proj->getGroup(); 

  }

  public function __toString() {
    global $_language,$_CMAPP;

    AMMain::addCommunicatorHandler('AMBProjectGroupAction');
    parent::add(CMHTMLObj::getScript("var AMBProjectGroup = new ambprojectgroupaction(AMBProjectGroupActionCallBack);"));

    parent::add("<b>$_language[project_group]</b></font><br>");

    parent::add('<div id="projectGroupList">');
    parent::add('</div>');

    parent::add("<a href=\"".$_CMAPP['services_url']."/projetos/members.php?frm_codProjeto=".$this->proj->codeProject."\"");
    parent::add(" class=\"green\">$_language[more_members]</a><br><br>");

    $js = 'loadProjrectGroup("'.$this->proj->codeGroup.'");';
    //parent::add(CMHTMLObj::addScript("AM_debugBrowserObject(AMBProjectGroup);"));
    parent::add(CMHTMLObj::addScript($js));

    return parent::__toString();
  }
}

?>