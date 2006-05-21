<?

class  AMBCommunitiesBigger extends AMColorBox {

  private $items = array();
  
  public function __construct() {
    global $_CMAPP;
    
    parent::__construct("$_CMAPP[imlang_url]/img_maiores_comunidades.gif", self::COLOR_BOX_BEGE);
    $this->items = $_SESSION[environment]->listBiggerCommunities();
  }

  public function __toString() {
    global $_CMAPP, $_language;
    
    if($this->items->__hasItems()) {
      foreach($this->items as $item) {
	parent::add("<a href=\"$_CMAPP[services_url]/communities/community.php?frm_codeCommunity=$item->code\" class=\"cinza\">");
	$mString = ($item->numItems > 1 ? "$item->numItems - $_language[members]" : "$item->numItems - $_language[member]");
	
	parent::add("$item->name - ($mString)");
	parent::add("</a><br>");
      }
    }else parent::add($_language[dont_have_communities]);
    
    return parent::__toString();

  }
}

?>