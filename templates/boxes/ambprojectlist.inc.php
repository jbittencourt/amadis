<?php
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

 class AMBProjectList extends AMPageBox implements CMActionListener {

 	private $itens = array();

 	public function __construct() {
 		parent::__construct(10);
 	}

 	public function doAction() {
 		global $_CMAPP, $_CMDEVEL, $_language;

 		$t = '<table cellspacing="0" cellpadding="0" border="0" width="100%">';
 		$ft = '</table>';
 		if(!isset($_REQUEST['list_action'])) $_REQUEST['list_action'] = "";
 		switch($_REQUEST['list_action']) {
 			default:
 				
 				$result = $_SESSION['environment']->listAllProjects($this->init, $this->numHitsFP);

 				$this->numItems = $result['count'];
 				$this->itens = $result[0];

 				$box = new AMProjectList($this->itens, $_language['list_projects'], AMTCadBox::CADBOX_LIST);


 				break;

 			case "A_list_areas":
 				$result = $_SESSION['environment']->listProjectsByArea($_REQUEST['frm_codArea'],$this->init, $this->numHitsFP);

 				$this->numItems = $result['count'];
 				$this->itens = $result[0];

 				if($this->itens->__hasItems()) {
 					list(,$item) = each($this->itens->items);
 					$areas = $item->area;
 					list(,$area) = each($areas->items);

 					reset($this->itens);
 				}

 				try {
 					$area = new AMArea;
 					$area->codeArea = $_REQUEST['frm_codArea'];
 					$area->load();
 				}catch (CMDBNoRecord $e) {
 					unset($area);
 				}

 				$title = "$_language[list_projects_areas] ".$area->name;
 				$box = new AMProjectList($this->itens, $title, AMTCadBox::CADBOX_LIST);


 				$this->addRequestVars("list_action=$_REQUEST[list_action]");

 				break;

 			case "A_list_communities" :
 				$result = $_SESSION['environment']->listProjectsCommunity($_REQUEST['frm_codeCommunity'],$this->init, $this->numHitsFP);
 				$this->numItems = $result['count'];
 				$this->itens = $result[0];
 				if($this->itens->__hasItems()) {
 					list(,$item) = each($this->itens->items);
 					$communities = $item->community;
 					list(,$community) = each($communities->items);

 					reset($this->itens);
 					$title = "$_language[list_projects_community] ".$community->name;
 				}

 				$title = "$_language[list_projects_community] ";
 				$box = new AMProjectList($this->itens,$title, AMTCadBox::CADBOX_LIST);

 				break;


 			case "A_list_comments" :
 				if(!empty($_REQUEST['frm_codProjeto'])) {
 					
 					$proj = new AMProject;
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

 					parent::add("<br /><span class=\"project-title\">$_language[project]: ".$proj->title."<br /></span>");
 					parent::add("<a  href='$_CMAPP[services_url]/projects/project.php?frm_codProjeto=$proj->codeProject' class='green'>");
 					parent::add("$_language[back_to_project]</a>");

 				} else {
 					header("Location:$_SERVER[PHP_SELF]?frm_amerror=any_project_id&list_action=fatal_error");
 				}
 				
 				break;

 			case "A_list_news" :

 				if(!empty($_REQUEST['frm_codProjeto'])) {
 					
 					$proj = new AMProject;
 					$proj->codeProject = $_REQUEST['frm_codProjeto'];
 					try {
 						$proj->load();
 					}catch(CMDBNoRecord $e) {
 						header("Location:$_SERVER[PHP_SELF]?frm_amerror=error_project_code_does_not_exists&list_action=fatal_error");
 					}

 					$result = $proj->listNews($this->init, $this->numHitsFP);

 					$this->addRequestVars("list_action=A_list_news&frm_codProjeto=$_REQUEST[frm_codProjeto]");

 					$this->numItems = $result['count'];
 					$this->itens = $result[0];
 					$title = "$_language[list_projects_news] $proj->title";
 					$box = new AMBProjectNewsList($this->itens, $title, AMTCadBox::CADBOX_LIST);

 					parent::add("<br /><span class=\"project-title\">$_language[project]: ".$proj->title."<br /></span>");
 					parent::add("<a  href='$_CMAPP[services_url]/projects/project.php?frm_codProjeto=$proj->codeProject' class='green'>");
 					parent::add("$_language[back_to_project]</a>");

 				} else {
 					header("Location:$_SERVER[PHP_SELF]?frm_amerror=any_project_id&list_action=fatal_error");
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