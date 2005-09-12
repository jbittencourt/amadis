<?

class AMBProjectComents extends AMSimpleBox implements CMActionListener {
  
  private $itens = array();

  public function __construct() {
    
    global $_CMAPP,$proj;

    parent::__construct($_CMAPP[imlang_url]."/img_comentarios_recebidos.gif");
    $comments = $proj->listComments();
    $this->itens = $comments[0];
    
  }

  public function doAction() {
    
    global $pag, $_language, $proj;
    
    switch($_REQUEST[comments_action]) {
    case "A_make_comment":
      try {
	$comment = new AMComment;
	$comment->codeUser = $_SESSION[user]->codeUser;
	$comment->desComentario = $_REQUEST[frm_desComentario];
	$comment->tempo = time();
	$comment->save();
	try {
	  $projComment = new AMProjectComment;
	  $projComment->codProjeto = $proj->codeProject;
	  $projComment->codComentario = $comment->codComentario;
	  $projComment->save();
	}catch (CMException $e) {
	  $comment->delete();
	}
	$pag->addMessage($_language[msg_save_comment_success]);
      } catch(CMException $e) {
	$pag->addError($_language[error_not_save_comment]);
      }
      break;
    }
  }

  public function __toString() {
    
    global $_CMAPP, $_language, $proj;
    
    /*
     *Buffering html of the box to output screen
     */
    if(!empty($this->itens->items)) {
      foreach($this->itens as $item) {
	
	parent::add("&nbsp;");
	parent::add(new AMTUserInfo($item->usuarios->items[0],AMTUserInfo::LIST_USERNAME));
	parent::add(": ".$item->desComentario."<br><br>");
      }
    }else parent::add("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$_language[no_comments]<br>");

    parent::add("<a href=\"".$_CMAPP[services_url]."/projetos/listprojects.php?list_action=A_list_comments&frm_codProjeto=".$proj->codeProject);
    parent::add("\" class=\"project_comment\">&raquo; $_language[more_comments]</a>");
    
    if(!empty($_SESSION[user])) {
      $addFriendBox = new AMTShowHide("addFriend", "<br>&raquo; $_language[add_project_comment]", AMTShowHide::HIDE);
      $addFriendBox->setClass('project_comment');
      $addFriendBox->add("<form method=\"post\" action=\"$_SERVER[PHP_SELF]\" name=\"add_comment\">"); //  Adicionar um coment
      $addFriendBox->add("<font class=texto>$_language[send_a_comment]</font><br>");
      $addFriendBox->add("<textarea cols=27 rows=5 name=\"frm_desComentario\"></textarea><br>");
      $addFriendBox->add("<input type=\"hidden\" name=\"comments_action\" value=\"A_make_comment\">");
      $addFriendBox->add("<input type=\"hidden\" name=\"frm_codProjeto\" value=\"$proj->codeProject\">");
      $addFriendBox->add("<input type=submit value=\"$_language[send]\">");
      $addFriendBox->add("</form>");
      parent::add($addFriendBox);
    }

    return parent::__toString();
      
  }
}

?>
