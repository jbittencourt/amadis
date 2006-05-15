<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBCommunitiesList extends AMPageBox implements CMActionListener {

  private $itens = array();
  protected $title;
  protected $box_type;
  
  public function __construct() {
    parent::__construct(10);
  }

  public function doAction() {
    global $_language;
    switch($_REQUEST[search_action]) {
    default:
      $resul = $_SESSION[environment]->listAllCommunities($this->init, $this->numHitsFP);
      $this->box_type = AMTCadBox::CADBOX_LIST;
      $this->title = $_language[communities_on_amadis];
      break;
    case "listing":
      $resul = $_SESSION[environment]->searchCommunities($_REQUEST[frm_search], $this->init, $this->numHitsFP);
      $this->box_type = AMTCadBox::CADBOX_SEARCH;
      $this->title = $_language[search_communities_result]." <font color=red>$_REQUEST[frm_search]</font>";
      break;
    }
    $this->itens = $resul[0];
    $this->numItems = $resul[count];
  }
  
  public function __toString() {
    global $_language, $_CMAPP;

    $t = "<table id='community_list'>";
    $ft = "</table>";

    $box = new AMTCadBox("", $this->box_type, AMTCadBox::COMMUNITY_THEME);
      
      
    $box->add($t);

    if($this->itens->__hasItems()) {
      $i = 0;
      foreach($this->itens as $item) {
	$id = "community_list_1";
	if(($i%2)==1) $id = "community_list_2";
	$i++;

	$box->add("<tr id='$id' class=\"community_list_line\"><td>");

	$f = $item->image;
	if($f!=0) {
	  $thumb = new AMCommunityThumb;
	  $thumb->codeArquivo = $item->image;
	  try {
	    $thumb->load();
	    $box->add($thumb->getView());
	  }
	  catch(CMDBException $e) {
	    echo $e; die();
	  }
	}
	else {
	  $box->add("&nbsp;");
	}


	$box->add("<td width=20%>");
	$box->add("<a href=\"".$_CMAPP[services_url]."/communities/community.php?frm_codeCommunity=".$item->code);
	$box->add("\" class=\"cinza\">".$item->name."</a><br>");
	$box->add("</td><td class=\"texto\">".nl2br($item->description)."</td>");
	$box->add("<td width=20%><a href=\"$_CMAPP[services_url]/communities/community.php?frm_codeCommunity=".$item->code."\" class=\"blue\">");
	$box->add("$_language[community_visit]</a></td></tr>");
      }
    }
    else {
      $box->add("<span class='texto'>$_language[no_communities_found]</a>");
    }
    $box->add($ft);
    $box->setTitle($this->title);
    parent::add($box);

    return parent::__toString();
  }
}
?>