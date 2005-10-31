<?
/**
 * This box lists the active requests from users to join a project
 *
 * @autho Juliano Bittencourt <juliano@lec.ufrgs.br>
 **/
class AMBProjectRequest extends AMColorBox implements CMActionListener {
    
  protected $requests;
  protected $proj;

  public function __construct(AMProjeto $proj) {
    global $_CMAPP;

    $this->requires("group.css",CMHTMLObj::MEDIA_CSS);
    $this->requires("group.js",CMHTMLObj::MEDIA_JS);

    $this->proj = $proj;
    $group = $proj->getGroup();

    parent::__construct("",self::COLOR_BOX_BLUE);

    $this->requests = $group->listGroupJoinRequests();
  }

  public function hasRequests() {
    return $this->requests->__hasItems();
  }


  public function doAction() {
    global $_CMAPP,$_language;
    if(!isset($_REQUEST['req_action']) || (isset($_REQUEST['req_action']) && empty($_REQUEST['req_action']))) {
      return false;
    }
    $group = $this->proj->getGroup();
    switch($_REQUEST['req_action']) {
    case "A_accept":
      //add the user to the project
      try {
	$group->acceptRequest($_REQUEST['frm_codeGroupJoin'],$_REQUEST['frm_text']);
	$msg = new AMMessage($user->name." ".$_language['msg_user_added'],get_class($this));
      }
      catch(CMDBException $e) {
	$err = new AMError($_language['error_joining_user'],get_class($this));
	return false;
      }
      break;
    case "A_reject":
      try {
	$group->rejectRequest($_REQUEST['frm_codeGroupJoin'],$_REQUEST['frm_text']);
	$msg = new AMMessage($user->name.' '.$_language['msg_rejected_user'],get_class($this));
      }
      catch(CMDBException $e) {
	//if occur some problem, remove the user
	$err = new AMError($_language['error_joining_user'],get_class($this));
	return false;
      }
      break;
    }

  }

  public function __toString() {
    global $_language,$_CMAPP;
    parent::add("<table border=0 cellspacing=1 cellpadding=2 width=\"100%\">");
    $_first = true;
    $proj = $this->proj;
    foreach($this->requests as $user) {
      if(!$_first) {
	parent::add("<tr><td colspan=4>");
	parent::add(new AMDotline("100%"));
      }
      
      //user foto thumbnail
      parent::add("<tr><td>");
      $thumb = new AMUserThumb;
      $thumb->codeArquivo = $user->foto;
      $thumb->load();
      parent::add($thumb->getView());

      //an empty column
      parent::add("</td><td><img src=\"$_CMAPP[images_url]/dot.gif\" width=10>");

      //invitation text
      parent::add("</td><td class=\"texto\">");
      parent::add(new AMTUserInfo($user));
      parent::add("$_language[user_join_request] ");

      $reason = $user->request[0]->textRequest;
      parent::add('<br><br><span class="texto">'.$reason.'</span>');
      parent::add("</td>");

      parent::add("</tr><tr><td colspan=3 class='response-button'>");


      $code = $user->request[0]->codeGroupMemberJoin;

      $input.= "<input type=hidden name=frm_codeGroupJoin value='$code'>";
      $input.= "<input type=hidden name=frm_codProjeto value='$proj->codeProject'>";

      $img_accept = "<img id='accept' onclick=\"doRequest('accept-box')\" class='response-button' src='$_CMAPP[imlang_url]/ico_aceitar.gif'>";
      $img_reject = "<img id='reject' onclick=\"doRequest('reject-box')\" class='response-button' src='$_CMAPP[imlang_url]/ico_rejeitar.gif'>";

      parent::add('<span id="group-buttons">'.$img_accept.' '.$img_reject.'</span>');

      parent::add("<div id='accept-box' class='response-box'>");
      parent::add("<form method=\"post\" action=\"$_SERVER[PHP_SELF]\" name=\"send_message\">");
      parent::add("<font class=texto>$_language[message]</font><br>");
      parent::add("<textarea cols=27 rows=5 name=\"frm_text\"></textarea><br>");
      parent::add("<input type=hidden name=req_action value='A_accept'>");
      parent::add($input); 
      parent::add("<button type=submit>$img_accept</button>");
      parent::add("</form>");
      parent::add("</div>");


      parent::add("<div id='reject-box' class='response-box'>");
      parent::add("<form method=\"post\" action=\"$_SERVER[PHP_SELF]\" name=\"send_message\">");
      parent::add("<font class=texto>$_language[message]</font><br>");
      parent::add("<textarea cols=27 rows=5 name=\"frm_text\"></textarea><br>");
      parent::add("<input type=hidden name=req_action value='A_reject'>");
      parent::add($input);
      parent::add("<button type=submit>$img_reject</button>");
      parent::add("</form>");
      parent::add("</div>");

      parent::add("</tr>");
      $_first = false;
    }
    parent::add("</table>");

    return parent::__toString();
  }

}

?>