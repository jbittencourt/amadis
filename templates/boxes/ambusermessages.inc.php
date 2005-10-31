<?

class AMBUserMessages extends AMColorBox {
  
  private $cProjects, $cDiary, $cMessages;

  public function __construct() {
    global $_CMAPP;
    
    parent::__construct("$_CMAPP[imlang_url]/box_comentarios.gif",AMColorBox::COLOR_BOX_BEGE);

    $this->requires("contextmenu.js",self::MEDIA_JS);

    $this->cProjects = $_SESSION['user']->getLastProjectsComments();
    $this->cDiary = $_SESSION['user']->getLastDiaryComments();
    //$this->cMessages = $_SESSION['user']->getLastMessages();
  
   }

  public function __toString() {
    global $_language, $_CMAPP;
    parent::add("<script>var contextMenuItems = new Array();</script>");
    parent::addPageEnd("<div id=\"AMContextMenu\" class=\"skin0\" display:none></div>");
    parent::addPageEnd("<script>initAMContextMenu();</script>");
    parent::addPageEnd("<script>initFrameMenu();</script>");
    
    if($this->cProjects->__hasItems()) {
      $this->__hasItems = true;
      parent::add("<b>&raquo;$_language[projects]</b><br>");
      $i = 0;
      foreach($this->cProjects as $item) {
	if($item->numMessages > 1) { 
	  parent::add("<script>contextMenuItems['show_$i'] = ");
	  parent::add("'$_CMAPP[services_url]/projetos/comments.php?tip=true&frm_codProjeto=$item->code';</script>");
	  parent::add("<a href=\"$_CMAPP[services_url]/projetos/projeto.php?frm_codProjeto=$item->code\" class=\"cinza\">");
	  parent::add("$item->numMessages $_language[project_comments] <b>$item->title</b></a><br>");
	  //parent::add("<img src=\"$_CMAPP[images_url]/arrow.gif\" id=\"show_$i\"><br>");
	} else {
	  parent::add("<script>contextMenuItems['show_$i'] = ");
	  parent::add("'$_CMAPP[services_url]/projetos/comments.php?tip=true&frm_codProjeto=$item->code';</script>");
	  parent::add("<a href=\"$_CMAPP[services_url]/projetos/projeto.php?frm_codProjeto=$item->code\" class=\"cinza\">");
	  parent::add("$item->numMessages $_language[project_comment] <b>$item->title</b></a><br>");
	  //parent::add("<img src=\"$_CMAPP[images_url]/arrow.gif\" id=\"show_$i\"><br>");
	}
	$i++;
      }
    } else $this->__hasItems = false;
    
    //listando comentarios do diario
    if($this->cDiary->__hasItems()) {
      $this->__hasItems = true;
      parent::add("<b>&raquo;$_language[diary]</b><br>");
      $i = 0;
      foreach($this->cDiary as $item) {
	if($item->numMessages > 1) { 
	  parent::add("<script>contextMenuItems['show_$i'] = ");
	  parent::add("'$_CMAPP[services_url]/projetos/comments.php?tip=true&frm_codProjeto=$item->code';</script>");
	  parent::add("<a href=\"$_CMAPP[services_url]/projetos/projeto.php?frm_codProjeto=$item->code\" class=\"cinza\">");
	  parent::add("$item->numMessages $_language[project_comments] <b>$item->title</b></a><br>");
	  //parent::add("<img src=\"$_CMAPP[images_url]/arrow.gif\" id=\"show_$i\"><br>");
	} else {
	  parent::add("<script>contextMenuItems['show_$i'] = ");
	  parent::add("'$_CMAPP[services_url]/projetos/comments.php?tip=true&frm_codProjeto=$item->code';</script>");
	  parent::add("<a href=\"$_CMAPP[services_url]/projetos/projeto.php?frm_codProjeto=$item->code\" class=\"cinza\">");
	  parent::add("$item->numMessages $_language[project_comment] <b>$item->title</b></a><br>");
	  //parent::add("<img src=\"$_CMAPP[images_url]/arrow.gif\" id=\"show_$i\"><br>");
	}
	$i++;
      }
    } else $this->__hasItems = false;
    
    if(!$this->__hasItems) parent::add("&nbsp;&nbsp;$_language[no_comments]");
  
  return parent::__toString();
  }
}



?>