<?
 
class AMBCommunityItems extends AMColorBox {
  
  private $link = array();
    
  public function __construct() {
    global $_CMAPP;
    parent::__construct($_CMAPP[imlang_url]."/img_itens_comunidade.gif",self::COLOR_BOX_LGREEN);
  }
    
  public function addLink($link, $url) {
    $this->links[] = array("url"=>$url,"link"=>$link);
  }

  public function __toString() {
    
    global $_CMAPP,$community, $_language;
    
    
    /*    
     *Buffering html of the box to output screen
     */
    //$urlforum =  //$_CMAPP[services_url]."/forum/forum.php?frm_type=community&frm_codCommunity=".$proj->code;

    $urlchat = $_CMAPP[services_url]."/chat/chat.php?frm_codComunidade=".$community->code;
    $urlleave = $_CMAPP[services_url]."/communities/community.php?frm_codeCommunity=".$community->code;
    
    parent::add("<a href=\"$urlforum\" class =\"boxlinkcomun\">&raquo; ".$_language[community_link_forum]."</a><br>");
    parent::add("<a href=\"$urlchat\" class =\"boxlinkcomun\">&raquo; ".$_language[community_link_chat]."</a><br>");
    parent::add("<a href=\"$urlleave\" class =\"boxlinkcomun\">&raquo; ".$_language[community_link_leave]."</a><br>");
	
      
    return parent::__toString();
      
  }
}

?>
