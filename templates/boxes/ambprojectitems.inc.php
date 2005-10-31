<?

class AMBProjectItems extends AMColorBox {
  
  private $link = array();
    
  public function __construct() {
    global $_CMAPP;
    parent::__construct($_CMAPP['imlang_url']."/box_itens_projeto.gif",self::COLOR_BOX_BLUA);
  }
    
  public function addLink($link, $url) {
    $this->links[] = array("url"=>$url,"link"=>$link);
  }

  public function __toString() {
    
    global $_CMAPP,$proj, $_language;
    
    
    /*    
     *Buffering html of the box to output screen
     */
    $urlpage = $_CMAPP['services_url']."/pages/viewpage.php?frm_page=projetos/projeto_".$proj->codeProject."&frm_codProjeto=".$proj->codeProject;
    $urlforum = $_CMAPP['services_url']."/projetos/projectforums.php?frm_codeProject=".$proj->codeProject;
    $urlchat = $_CMAPP['services_url']."/chat/chat.php?frm_codProjeto=".$proj->codeProject;
    
    parent::add("<a href=\"$urlpage\" class=\"green\">&raquo; ".$_language['project_link_page']."</a><br>");
    parent::add("<a href=\"$urlforum\" class =\"green\">&raquo; ".$_language['project_link_forum']."</a><br>");
    parent::add("<a href=\"$urlchat\" class =\"green\">&raquo; ".$_language['project_link_chat']."</a><br>");
  
	
      
    return parent::__toString();
      
  }
}

?>
