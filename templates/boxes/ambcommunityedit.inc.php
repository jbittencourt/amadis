<?

class AMBCommunityEdit extends AMColorBox {
  
  private $itens = array();
    
  public function __construct() {
    global $_CMAPP;
    //img_novidades_projetos.gif
    parent::__construct($_CMAPP[imlang_url]."/img_edicao_comunidade.gif",self::COLOR_BOX_BEGE);
  }
    
  public function addItem($item) {
    $this->itens[] = $item;
  }

  public function __toString() {
    
    global $_CMAPP, $community, $_language;
  
    /*
     *Load a lang file
     */ 
    $lang = $_CMAPP[i18n]->getTranslationArray("communities");

    /*    
     *Buffering html of the box to output screen
     */

    

    $urledit = $_CMAPP[services_url]."/communities/editcommunitydata.php?frm_codeCommunity=".$community->code;
    $urlmembers = $_CMAPP[services_url]."/communities/managemembers.php?frm_codeCommunity=".$community->code;
    $urlprojects = $_CMAPP[services_url]."/communities/managemembers.php?frm_codeCommunity=".$community->code;
    // $urlchangeimage = $_CMAPP[services_url]."/communities/changeimage.php?frm_codeCommunity=".$community->code;

    
    parent::add("<a href=\"$urledit\" class =\"cinza\">&raquo; ".$_language[community_link_edit]."</a><br>");
    parent::add("<a href=\"$urlmembers\" class =\"cinza\">&raquo; ".$_language[community_link_members]."</a><br>");
    parent::add("<a href=\"$urlmembers\" class =\"cinza\">&raquo; ".$_language[community_link_members]."</a><br>");
    
    //parent::add("<a href=\"$urlchangeimage\" class =\"cinza\">&raquo; ".$_language[community_link_change_image]."</a><br>");

    return parent::__toString();
      
  }
}

?>
