<?php
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBMyCommunities extends AMColorBox {

  private $itens = array();

  public function __construct() {
    global $_CMAPP; 
    
    parent::__construct("$_CMAPP[imlang_url]/img_minhas_comunidades.gif",self::COLOR_BOX_BEGE);
    
    $this->itens = $_SESSION['user']->listMyCommunities();

  }

  public function __toString() {
    global $_CMAPP, $_language;

    if(!empty($this->itens->items)) {
      foreach($this->itens as $item) {
	parent::add("<a class=\"cinza\" href=\"$_CMAPP[services_url]/communities/community.php?frm_codeCommunity=$item->code\">");
	parent::add("&raquo; $item->name</a><br />");
      }
    } else {parent::add($_language['dont_have_communities']);}
    
    return parent::__toString();

  }

}