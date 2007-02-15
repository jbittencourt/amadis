<?php
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBProjectEdit extends AMColorBox implements CMActionListener {
  
	private $itens = array();
  	protected $proj;
  	protected $abandoned=false;
    
  	public function __construct(AMProject $proj) 
  	{
    	global $_CMAPP;
    	$this->proj = $proj;
    	//img_novidades_projetos.gif
    	parent::__construct($_CMAPP['imlang_url']."/box_edicao_projeto.gif",self::COLOR_BOX_GREEN);
  	}

  	/**
   	 * Excute some actions that this box trigger. The action preffix is pe (Project Edit).
   	 **/
  	public function doAction() 
  	{
		global $_CMAPP;
    	if(empty($_REQUEST['pe_action'])) return false;
    	switch($_REQUEST['pe_action']) {
    		case "A_leave":
      			$group = $this->proj->getGroup();
      			try {
					$group->retireMember($_SESSION['user']->codeUser);
    	  		}
      			catch(CMDBException $e) {
					$err = new CMError($_language['error_cannot_leave_project'],__CLASS__);
					return false;
	      		}
    	  		CMHTMLPage::redirect($_CMAPP['services_url'].'/projects/project.php?frm_codProjeto='.$_REQUEST['frm_codProjeto'].'&frm_message=leave_project');
      			$this->abandoned = true;
	
    	  		//clear group cache
      			if(!empty($_SESSION['amadis']['projects']))  unset($_SESSION['amadis']['projects']);
      			break;
    	}
  }

    
  	public function addItem($item) 
  	{
    	$this->itens[] = $item;
  	}

  	public function __toString() 
  	{  
    	global $_CMAPP, $proj, $_language;
  
    	$link = $_CMAPP['services_url']."/projects/project.php?frm_codProjeto=$proj->codeProject";
    	if($this->abandoned) {
      		CMHTMLPage::redirect($link);
    	}

    	/*
     	 * Load a lang file
     	 */ 
    	$lang = $_CMAPP['i18n']->getTranslationArray("projects");


    	/*    
     	 * Buffering html of the box to output screen
     	 */
    	$urlinfo = $_CMAPP['services_url']."/projects/edit.php?frm_codeProject=".$proj->codeProject;
    	$urlequipe = $_CMAPP['services_url']."/projects/inviteusers.php?frm_codeProjeto=".$proj->codeProject;
    	$urlupload = $_CMAPP['services_url']."/upload/upload.php?frm_upload_type=project&frm_codeProjeto=".$proj->codeProject;
    	$leave_confirm = "if(confirm(\"$_language[project_leave_confirm]\")) { window.location=\"$_SERVER[PHP_SELF]?pe_action=A_leave&frm_codProjeto=".$proj->codeProject."\";  } else { return false; }";
    	$urlleave = $_CMAPP['services_url']."/projects/project.php?frm_codeProjeto=".$proj->codeProject;
    	$urleditimage = $_CMAPP['services_url']."/projects/edit_image.php?frm_codeProject=".$proj->codeProject;
    	$urleditareas = $_CMAPP['services_url']."/projects/edit_areas.php?frm_codeProject=".$proj->codeProject;

    	parent::add("<a href=\"$urlinfo\" class =\"green\">&raquo; ".$_language['project_link_info']."</a><br>");
    	parent::add("<a href=\"$urleditimage\" class =\"green\">&raquo; ".$_language['project_link_image']."</a><br>");
    	parent::add("<a href=\"$urleditareas\" class =\"green\">&raquo; ".$_language['project_link_areas']."</a><br>");
    	parent::add("<a href=\"$urlequipe\" class =\"green\">&raquo; ".$_language['project_link_group']."</a><br>");
    	parent::add("<a href=\"$urlupload\" class =\"green\">&raquo; ".$_language['project_link_upload']."</a><br>");
    	parent::add("<a href=\"#\" onClick='$leave_confirm' class =\"green\">&raquo; ".$_language['project_link_leave']."</a><br>");

    	return parent::__toString();
      
  }
}