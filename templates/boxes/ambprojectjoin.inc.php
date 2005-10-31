<?

class AMBProjectJoin extends AMColorBox implements CMActionListener {

  protected $proj;

  public function __construct(AMProjeto $proj) {
    global $_CMAPP;
    $this->proj = $proj; 
    parent::__construct($_CMAPP['imlang_url']."/box_edicao_projeto.gif",self::COLOR_BOX_GREEN);

  }

  public function doAction() {
    global $_CMAPP,$_language;

    if(!isset($_REQUEST['join_action']) || (isset($_REQUEST['join_action']) && empty($_REQUEST['join_action']))) 
      return false;

    switch($_REQUEST['join_action']) {
    case "A_join":
      if(!empty($_REQUEST['frm_text'])) {
	$group = $this->proj->getGroup();
	try {
	  $group->userRequestJoin($_SESSION['user']->codeUser,$_REQUEST['frm_text']);
	}
	catch(CMDBException $e) {
	  $err = new AMError($_langauge['error_join_request'],get_class($this));
	  return false;
	}
	$men = new AMMessage($_language['msg_join_request_send'],get_class($this));
      }
      else {
	$mem = new AMError($_language['error_no_explain_join_project'],get_class($this));
      }
      break;
    }
  }

  public function __toString() {
    global $_CMAPP,$_language;
    $group = $this->proj->getGroup();

    
    if(!$group->hasRequestedJoin($_SESSION['user']->codeUser)) {
      $box = new AMTShowHide("sendMessage", "$_language[project_join]", AMTShowHide::HIDE);
      $box->add("<form method=\"post\" action=\"$_SERVER[PHP_SELF]\" name=\"send_message\">");
      $box->add("<font class=texto>$_language[project_join_request]</font><br>");
      $box->add("<textarea cols=27 rows=5 name=\"frm_text\"></textarea><br>");
      $box->add("<input type=\"hidden\" name=\"join_action\" value=\"A_join\">");
      $box->add("<input type=\"hidden\" name=\"frm_codProjeto\" value=\"".$this->proj->codeProject."\">");
      $box->add("<input type=submit value=\"$_language[send]\">");
      $box->add("</form>");
      parent::add($box);
    }
    else {
      parent::add("<p align=center>$_language[request_join_waiting]</p>");
    }

    return parent::__toString();
  }

}



?>