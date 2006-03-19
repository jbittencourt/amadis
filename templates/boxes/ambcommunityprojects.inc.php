<?

class AMBCommunityProjects extends AMSimpleBox {

  private $itens = array();
  
  public function __construct() {
    global $_CMAPP, $community;

    parent::__construct("$_CMAPP[imlang_url]/img_cm_projetos_ligados.gif");

    $this->itens = $community->listProjects();

  }

  public function __toString() {
    global $_CMAPP, $_language, $community;
    

    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"10\"><br>");

    if($this->itens->__hasItems()) {
      foreach($this->itens as $item) {
	parent::add("&nbsp;&nbsp;&nbsp;&raquo;");
	parent::add("<a href=\"$_CMAPP[services_url]/projetos/projeto.php?frm_codProjeto=$item->codeProject\" class=\"cinza\">$item->title</a>");
	parent::add("<br>");
      }
      parent::add("<a href=\"".$_CMAPP[services_url]."/communities/listcommunities.php?frm_codeCommunity=".$community->code."&list_action=A_list_projects\" class=\"cinza\">&nbsp;&nbsp;$_language[more_projects]</a><br><br>");
    }
    else { 
      parent::add("&nbsp;&nbsp;&nbsp;".$_language[no_projects]."<br><br>");
    }
      return parent::__toString();
      
  }
}

?>