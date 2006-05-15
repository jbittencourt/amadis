<?

/**
 * This box lists the active requests from users to join a project
 *
 * @package AMADIS
 * @subpackage AMBoxes
 * @autho Juliano Bittencourt <juliano@lec.ufrgs.br>
 **/
class AMBProjectOrfan extends AMColorBox implements CMActionListener {
    
  protected $requests;
  protected $proj;
  protected $adopted = false;

  public function __construct(AMProjeto $proj) {
    global $_CMAPP;
    //parent::__construct($_CMAPP[imlang_url]."/img_novidades_projetos.gif");
    $this->proj = $proj;
    parent::__construct("",self::COLOR_BOX_BLUE);
    
    $group = $proj->getGroup();
    $this->requests = $group->listGroupJoinRequests();
   
  }


  public function doAction() {
    global $_CMAPP,$_language;

    if(!isset($_REQUEST['po_action']) || (isset($_REQUEST['po_action']) && empty($_REQUEST['po_action']))) {
      return false;
    }

    switch($_REQUEST['po_action']) {
    case "A_adopt":
      //add the user to the project
      $group = $this->proj->getGroup();
      $group->force_add = true;
      try {
	$group->addMember($_SESSION['user']->codeUser);
      }
      catch(CMDBException $e) {
	$err = new AMError($_language['error_joining_user'],get_class($this));
	return false;
      }

      
      $msg = new AMMessage($user->name." ".$_language['msg_project_adopted'],get_class($this));
      $this->adopted = true;
      break;
    }

  }

  public function __toString() {
    global $_language,$_CMAPP;

    $proj = $this->proj;

    $link = $_CMAPP['services_url']."/projetos/projeto.php?frm_codProjeto=$proj->codeProject";
    if($this->adopted) {
      CMHTMLPage::redirect($link);
      return false;
    }
    parent::add("<table border=0 cellspacing=1 cellpadding=2 width=\"100%\"><tr>");

    //an empty column
    parent::add("<td><img src=\"$_CMAPP[images_url]/dot.gif\" width=10>");

    //an empty column
    parent::add("<td><img src=\"$_CMAPP[images_url]/dot.gif\" width=10>");
    //invitation text
    parent::add("</td><td class=\"texto\">");
    parent::add("$_language[project_orfan] ");
    parent::add("</td><td align=center>");

    $js = "if(confirm('$_language[project_adopt_confirm]')) { window.location= '$link&po_action=A_adopt'; } else { return false; }";
    parent::add("<a href=\"#\" onClick=\"$js\"class=\"green\">$_language[project_adopt]</a><br>");
    parent::add("</tr>");

    parent::add("</table>");

    return parent::__toString();
  }

}

?>