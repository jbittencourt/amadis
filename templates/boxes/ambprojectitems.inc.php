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
  
  public static function getPageButton($proj){
    global $_CMAPP,$_language;

    $temp = new CMWSwapImage("#",$_CMAPP['imlang_url'].'/icon_page_off.gif',$_CMAPP['imlang_url'].'/icon_page_on.gif');
    $urlpage = $_CMAPP['services_url']."/pages/viewpage.php?frm_page=projetos/projeto_".$proj."&frm_codProjeto=".$proj;
    return '<button class="project_items button-as-link" type="button" onClick="AM_openURL(\''.$urlpage.'\')">'.$temp->__toString().'<br><font class="project_items_text"> '.$_language['project_link_pagebutton'].'</font></button>'; 
  }

  public static function getForumButton($proj){
    global $_CMAPP,$_language;
    
    $urlforum = $_CMAPP['services_url']."/projetos/projectforums.php?frm_codeProject=".$proj;
    return '<button class="project_items button-as-link" type="button" onClick="AM_openURL(\''.$urlforum.'\')"><img src="'.$_CMAPP['imlang_url'].'/icon_forum_off.gif"><br><font class="project_items_text"> '.$_language['project_link_forumbutton'].'</font></button>'; 
  }

  public static function getChatButton($proj){
    global $_CMAPP,$_language;
    
    $urlchat = $_CMAPP['services_url']."/chat/chat.php?frm_codProjeto=".$proj;
    return '<button class="project_items button-as-link" type="button" onClick="AM_openURL(\''.$urlchat.'\')"><img src="'.$_CMAPP['imlang_url'].'/icon_chat_off.gif"><br><font class="project_items_text"> '.$_language['project_link_chatbutton'].'</font></button>'; 
  }

  public static function getDiaryButton($proj){
    global $_CMAPP,$_language;
    
    $urldiary = "";
    return '<button class="project_items button-as-link" type="button" onClick="AM_openURL(\''.$urldiary.'\')"><img src="'.$_CMAPP['imlang_url'].'/icon_diario_off.gif"><br><font class="project_items_text"> '.$_language['project_link_diarybutton'].'</font></button>'; 
  }

  public function __toString() {
    
    global $_CMAPP,$proj, $_language;   
    
    /*    
     *Buffering html of the box to output screen
     */    
    parent::add($this->getPageButton($proj->codeProject));
    parent::add($this->getDiaryButton($proj->codeProject));
    parent::add($this->getForumButton($proj->codeProject));
    parent::add($this->getChatButton($proj->codeProject));
      
    return parent::__toString();
      
  }
}

?>
