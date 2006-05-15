<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBCommunityList extends AMPageBox implements CMActionListener {

  private $itens = array();
  
  public function __construct() {
    parent::__construct(10);
  }

  public function doAction() {
    global $_CMAPP, $_CMDEVEL, $_language;
    
    $t = "<table cellspacing=0 cellpadding=0 border=0 width=100%>";
    $ft = "</table>";
    if(!isset($_REQUEST['list_action'])) $_REQUEST['list_action'] = "";

    switch($_REQUEST['list_action']) {
    default:
      $result = $_SESSION['environment']->listAllCommunities($this->init, $this->numHitsFP);
      
      $this->numItems = $result['count'];
      $this->itens = $result[0];

      $box = new AMCommunityList($this->itens, $_language['list_communities'], AMTCadBox::CADBOX_LIST);
      

      break;
      
    case "A_list_projects" :
      $result = $_SESSION['environment']->listProjectsCommunity($_REQUEST['frm_codeCommunity']);
      $this->numItems = $result['count'];
      $this->itens = $result[0];
      if($this->itens->__hasItems()) {
 	list(,$item) = each($this->itens->items);
 	$communities = $item->community;
 	list(,$community) = each($communities->items);
	
 	reset($this->itens);       
       }

      $title = "$_language[list_projects_community] ".$community->name;
      $box = new AMProjectList($this->itens,$title, AMTCadBox::CADBOX_LIST);
      break;


    case "A_list_comments" :
      //header("Location:$_SERVER[PHP_SELF]?frm_amerror=under_construction&list_action=fatal_error");
      if(!empty($_REQUEST['frm_codProjeto'])) {
	
	$proj = new AMProjeto;
	$proj->codeProject = $_REQUEST['frm_codProjeto'];
	try {
	  $proj->load();
	}catch(CMDBNoRecord $e) {
	  header("Location:$_SERVER[PHP_SELF]?frm_amerror=error_project_code_does_not_exists&list_action=fatal_error");
	}
	
	$result = $proj->listComments($this->init, $this->numHitsFP);
	
	$this->numItems = $result['count'];
	$this->itens = $result[0];
	$title = "$_language[list_projects_comments] $proj->title";
	$box = new AMBProjectCommentsList($this->itens, $title, AMTCadBox::CADBOX_LIST);
	
	parent::add("<br><span class=\"project_title\">$_language[project]: ".$proj->title."<br></span>");
	parent::add("<a  href='$_CMAPP[services_url]/projetos/projeto.php?frm_codProjeto=$proj->codeProject' class='green'>");
	parent::add("$_language[back_to_project]</a>");

      } else {
	header("Location:$_SERVER[PHP_SELF]?frm_amerror=any_project_id&list_action=fatal_error");
      }
      
      break;

    case "A_list_news" :
      if(!empty($_REQUEST['frm_codeCommunity'])) {
	$co = new AMCommunities;
	$co->code = $_REQUEST['frm_codeCommunity'];
	try {
	  $co->load();
	}catch(CMDBNoRecord $e) {
	  header("Location:$_SERVER[PHP_SELF]?frm_amerror=error_no_community_id&list_action=fatal_error");
	}

	$result = $co->listNews($this->init, $this->numHitsFP);
	//	note($result);die(); 

	$this->addRequestVars("list_action=A_list_news&frm_codeCommunity=$_REQUEST[frm_codeCommunity]");
	
	$this->numItems = $result['count'];
	$this->itens = $result[0];
	$title = "$_language[list_community_news] $co->name";
	$box = new AMBCommunityNewsList($result, $title, AMTCadBox::CADBOX_LIST);
	
	parent::add("<br><span class=\"community_title\">$_language[community]: ".$co->name."<br></span>");
	parent::add("<a  href='$_CMAPP[services_url]/communities/community.php?frm_codeCommunity=$co->code' class='cinza'>");
	parent::add("$_language[back_to_community]</a>");

      } else {
	header("Location:$_SERVER[PHP_SELF]?frm_amerror=any_community_id&list_action=fatal_error");
      }
      
      break;
     
    case "fatal_error":

      break;
    }
    parent::add($box);
  }
  
  public function __toString() {
    return parent::__toString();
  }
}
?>