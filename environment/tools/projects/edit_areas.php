<?php
/**
 * This page creates a new project.
 *
 * This page is used to create a new project in AMADIS. It
 * has 3 pages: one for the general project data (name, description, etc),
 * other to select areas and a third for the project image selection. The
 * only user that is added in this stage to the project is the logged user.
 * 
 *
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 **/
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("project_create");


$pag = new AMTCadProj();

/*
 *Load language module
 */
    
$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;
    

//if is editing an project, load the data in the object
if(!empty($_REQUEST['frm_codeProject'])) {
	$_SESSION['cad_proj'] = new AMProject;
  	$_SESSION['cad_proj']->codeProject = $_REQUEST['frm_codeProject'];
  	try {
    	$_SESSION['cad_proj']->load();
  	} catch(CMObjException $e) {
  		new AMErrorReport($e, 'AMProject::load', AMLog::LOG_PROJECTS );
  	}

  	$group = $_SESSION['cad_proj']->getGroup();

  	if(isset($_REQUEST['frm_codAreas']) && is_array($_REQUEST['frm_codAreas'])) {
    	//I use a temp var to store the areas because a construction of the type 
    	//$_SESSION[cad_proj]->areas[] is not allowed when using an propertie setted by _set()
    	$tmp_areas = $_SESSION['cad_proj']->areas;
    	foreach($_REQUEST['frm_codAreas'] as $area) {
      		$tmp_areas[] = $area;
    	}
    	$_SESSION['cad_proj']->addVariable("areas",$tmp_areas);
  	}

  	if(!$group->isMember($_SESSION['user']->codeUser)) {
    	CMHTMLPage::redirect($_CMAPP['services_url']."/projects/project.php?frm_codProjeto=$_REQUEST[frm_codeProject]&frm_amerror=edit_not_allowed");
  	}
}

//form box to interface
$cadBox = new AMTCadBox("",AMTCadBox::CADBOX_CREATE,AMTCadBox::PROJECT_THEME);

switch($_REQUEST['action']) {

 	default:
   		$form = new AMWSmartForm('AMProject',"select_areas",$_SERVER['PHP_SELF']);

   		$areas = $_SESSION['environment']->listAreas();
   		$form->addComponent("acao", new CMWHidden("action","pag_2"));

   		if($_SESSION['cad_proj'] instanceof CMObj) {
     		if($_SESSION['cad_proj']->state!=CMObj::STATE_DIRTY_NEW && $_SESSION['cad_proj']->state!=CMObj::STATE_NEW) {
       			try {
	 				$proj_areas = $_SESSION['cad_proj']->listAreas();
		 			$areas->sub($proj_areas);
    	   		} catch(CMObjEPropertieNotDefined $e) {
	 				$proj_areas = array();
	 				$_SESSION['cad_proj']->addVariable("areas",array());
       			}
	     	}
   		}
   
		if(!isset($proj_areas)) $proj_areas=array();
	   	$lista = new CMWListAdd("frm_codeArea",$areas,$proj_areas,"codeArea","name");
   		$form->addComponent("frm_codeArea",$lista);

   		$form->setCancelUrl("$_CMAPP[services_url]/projects/projects.php?clear_cadProj");
   		$cadBox->add($form);

	   	$cadBox->setTitle("<img src='$_CMAPP[imlang_url]/img_areas_conhecimento.gif'>");
   		break;

   	case "pag_2":
   		if(!isset($_REQUEST['frm_codeArea'])) $_REQUEST['frm_codeArea']=array();

   		if((!is_array($_REQUEST['frm_codeArea']) && empty($_FILES['frm_foto']))) {
     		Header("Location: $_CMAPP[services_url]/projects/create.php?action=pag_1&frm_amerror=proj_must_select_areas");
   		} else {
     		if(is_array($_REQUEST['frm_codeArea'])) {  	  
       			$temp = array();
       			foreach($_REQUEST['frm_codeArea'] as $area) {
	 				$temp[] = $area;
       			}
       			$_SESSION['cad_proj']->addVariable("areas",$temp);
     		}
   		}

   		//save the areas
   		$con = new CMContainer;

   		$proj_areas = $_SESSION['cad_proj']->listAreas();

   		$tmp_areas = $_SESSION['cad_proj']->areas;
   		$max = 0;

   		foreach($tmp_areas as $code) {
   			if($proj_areas->in($code)) {
       			$proj_areas->remove($code);
  				continue;
    		}     
    		$temp = new AMProjectArea;
    		$temp->codeArea = $code;
    		$temp->codeProject = $_SESSION['cad_proj']->codeProject;     
    		$con->add($code,$temp);
    		$max = max($max,$code);     
   		}
   		$remove = new CMContainer;
   		if($proj_areas->__hasItems()) {
    		foreach($proj_areas as $item) {
       			$temp = $item->areas;
	       		$temp->items[0]->codeArea = $item->codeArea; //override devel bug
    	   		$remove->add($item->codeArea,$temp->items[0]);
     		}
   		}


   		try {
     		$remove->acidOperation(CMContainer::OPERATION_DELETE);
     		$con->acidOperation(CMContainer::OPERATION_SAVE);
   		} catch(CMObjEContainerOperationFailed $e) {
     		Header("Location: $_CMAPP[services_url]/projects/create.php?action=fatal_error&frm_amerror=save_failed");
   		}
   		$cod = $_SESSION['cad_proj']->codeProject;
   		unset($_SESSION['cad_proj']);
   		unset($_SESSION['cad_foto']);
   
   		//if everything was ok, go the page of the project.
   		CMHTMLPage::redirect($_CMAPP['services_url'].'/projects/project.php?frm_ammsg=areas_changed&frm_codProjeto='.$cod);
   
  		break;
	case "fatal_error":
   		//No caso de um erro fatal.
   		//A mensagem de erro e exibida pelo proprio template AMMain.
   		$cadBox->add("<div align=center><a href=\"$_SERVER[PHP_SELF]\">$_language[try_again]</a></div>");
   		break;
}
   
$pag->add($cadBox);
echo $pag;