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

} else {
	$pag->addError($_language['error_any_project_id'], '');
	echo $pag;
	die();
}


(!isset($_REQUEST['action']) ? $_REQUEST['action']="" : '');

//form box to interface
$cadBox = new AMTCadBox("",AMTCadBox::CADBOX_CREATE,AMTCadBox::PROJECT_THEME);

switch($_REQUEST['action']) {

 	default:

   		$fields_rec = array("title","description");
      
   		//formulary
   		$form = new AMWSmartForm('AMProject',"cad_user",$_SERVER['PHP_SELF'],$fields_rec);
   
   		$form->setCancelUrl("$_CMAPP[services_url]/projects/projects.php?clear_cadProj");

   		$form->loadDataFromObject($_SESSION['cad_proj']);
   
		$form->addComponent("action",new CMWHidden("action","pag_1"));
		$form->addComponent("codeProject",new CMWHidden("frm_codeProject",$_REQUEST['frm_codeProject']));
		
   		$cadBox->add($form);
   		$cadBox->setTitle("<img src='$_CMAPP[imlang_url]/img_dados_gerais.gif'>");
   		break;
      
 case "pag_1":

   if(!($_SESSION['cad_proj'] instanceof AMProject)) {
     //if this is the first submit, create an object in the session to store the user data
     $_SESSION['cad_proj'] = new AMProject();
     $_SESSION['cad_proj']->loadDataFromRequest();
   } else {
     //if the user hit back, fill the from with the data from the session object
     $_SESSION['cad_proj']->loadDataFromRequest();
   }

   	//save the project
	try {
    	$_SESSION['cad_proj']->save();
   	} catch(CMDBException $e) {
		new AMErrorReport($e, 'AMProject::save',AMLog::LOG_PROJECTS );	
		header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_project");
   	} catch(AMException $e) {
		new AMErrorReport($e, 'AMProject::save',AMLog::LOG_PROJECTS );
		header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=creating_project_dir");
   	}

   	$cod = $_SESSION['cad_proj']->codeProject;
   	unset($_SESSION['cad_proj']);
   
   	//if everything was ok, go the page of the project.
   	CMHTMLPage::redirect($_CMAPP['services_url'].'/projects/project.php?frm_ammsg=project_edited&frm_codProjeto='.$cod);
   
  	break;


 case "fatal_error":
   //No caso de um erro fatal.
   //A mensagem de erro e exibida pelo proprio template AMMain.
   $cadBox->add("<div align=center><a href=\"$_SERVER[PHP_SELF]\">$_language[try_again]</a></div>");
   break;
}
   
$pag->add($cadBox);
echo $pag;