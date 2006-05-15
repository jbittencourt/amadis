<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBCommunityEdit extends AMColorBox {
  
  private $itens = array();
  protected $abandoned=false;
    
  public function __construct() {
    global $_CMAPP;
    parent::__construct($_CMAPP[imlang_url]."/img_edicao_comunidade.gif",self::COLOR_BOX_BEGE);
  }
    
  public function addItem($item) {
    $this->itens[] = $item;
  }





  public function __toString() {
    global $_CMAPP, $community, $_language;

    /*    
     *Buffering html of the box to output screen
     */

    $urledit = $_CMAPP[services_url]."/communities/update.php?frm_codeCommunity=".$community->code;
    //$urlmembers = $_CMAPP[services_url]."/communities/managemembers.php?frm_codeCommunity=".$community->code;
  
    $urlinvite = $_CMAPP['services_url']."/communities/inviteusers.php?frm_codeCommunity=$community->code";
    $urlproject = $_CMAPP['services_url']."/communities/tieproject.php?frm_codeCommunity=$community->code";

    parent::add("<a href=\"$urledit\" class =\"cinza\">&raquo; ".$_language[community_link_edit]."</a><br>");
    parent::add("<a href=\"$urlinvite\" class =\"cinza\">&raquo; ".$_language[community_link_invite]."</a><br>");    
    parent::add("<a href=\"$urlproject\" class =\"cinza\">&raquo; ".$_language[community_link_project]."</a><br>");
   
    return parent::__toString();
      
  }
}

?>
