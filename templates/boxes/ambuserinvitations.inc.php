<?

/**
 *Este eh box de aviso de convite e aviso de requisicao de entrada
 *para um projeto.
 * 
 * @package AMADIS
 * @subpackage AMBoxes
 */


class AMBUserInvitations extends AMColorBox implements CMActionListener {
    
  protected $invitations, $__hasItems;

  public function __construct() {
    global $_CMAPP;
    parent::__construct("",self::COLOR_BOX_BLUE);

    try {
      $this->invitations = $_SESSION['user']->listProjectsInvitations();
      ($this->invitations->__hasItems() ? $this->__hasItems = true : $this->__hasItems = false);
    }catch(AMWEFirstLogin $e) {
      $this->__hasItems = false;
    }
    
  }

  public function __hasInvitations() {
    return $this->__hasItems;
  }


  public function doAction() {
    global $_CMAPP,$_language;
    
    if(!isset($_REQUEST['inv_action'])) {
      return false;
    }

    try {
      $proj = new AMProject;
      $proj->codeProject = $_REQUEST['inv_codeProject'];
      $proj->load();
      
      $group = $proj->getGroup();
    }
    catch(CMDBNoRecord $e) {
      note($e);die();
      $err = new AMError($_language['error_joining_project'],get_class($this));
      return false;
    }


    switch($_REQUEST['inv_action']) {
    case "A_accpect":
      //add the user to the project
      try {
	$group->acceptInvitation($_REQUEST['inv_codeGroupMemberJoin'], "");
	unset($_SESSION['amadis']['projects']);
      }
      catch(CMGroupException $e) {
	$err = new AMError($_language['error_joining_project'],get_class($this));
	return false;
      }

      $msg = new AMMessage($_language['msg_joined_project']." $proj->title.",get_class($this));
      break;

    case "A_reject":
      try {
	$group->rejectInvitation($_REQUEST['inv_codeGroupMemberJoin'], "");
      }
      catch(CMGroupException $e) {
	$err = new AMError($_language['error_joining_project'],get_class($this));
	return false;
      }
      $msg = new AMMessage($_language['msg_rejected_project']." $proj->title.",get_class($this));
      break;
    }

  }

  public function __toString() {
    global $_language,$_CMAPP;
    if($this->__hasItems) {
      parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=20 height=20>");
      parent::add("<table border=0 cellspacing=1 cellpadding=2 width=\"100%\">");
      $_first = true;
      foreach($this->invitations as $proj) {
	if(!$_first) {
	  parent::add("<tr><td colspan=4>");
	  parent::add(new AMDotline("100%"));
	}
	
	//project image thumbnail
	parent::add("<tr><td>");
	$thumb = new AMProjectThumb;
	$f = $proj->image;
	if(empty($f)) $f = AMProjImage::DEFAULT_IMAGE;
	$thumb->codeArquivo = $f;
	try {
	  $thumb->load();
	} catch(CMDBNoRecord $e) {}

	parent::add($thumb->getView());
	
	//an empty column
	parent::add("</td><td><img src=\"$_CMAPP[images_url]/dot.gif\" width=10>");

	//invitation text
	parent::add("</td><td class=\"texto\">");
	parent::add($_language['project_invitation'].' ');
	parent::add("<a class=\"blue\" href=\"$_CMAPP[services_url]/projects/projeto.php?frm_codProjeto=$proj->codeProject\">$proj->title</a>.");
	parent::add("</td><td align=center>");

	$inv = $proj->invitation[0];
	$link = $_CMAPP['services_url']."/webfolio/webfolio.php?inv_codeProject=$proj->codeProject&inv_codeGroupMemberJoin=".$inv->codeGroupMemberJoin;

	parent::add("<a href=\"$link&inv_action=A_accpect\" class=\"blue\">$_language[accept]</a><br>");
	parent::add("<a href=\"$link&inv_action=A_reject\"class=\"blue\">$_language[reject]</a>");
	parent::add("</tr>");
	$_first = false;
      }
      parent::add("</table>");

      return parent::__toString();
    }
  }
}