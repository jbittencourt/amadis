<?php
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

  $_SESSION['cad_foto'] = new AMProjectImage;
  $_SESSION['cad_foto']->codeFile = (integer) $_SESSION['cad_proj']->image;
  try {
    $_SESSION['cad_foto']->load();
  }  catch (CMDBQueryError $e) {
    $_SESSION['cad_foto'] = new AMProjectImage;
  }
       
  if(!$group->isMember($_SESSION['user']->codeUser)) {
    CMHTMLPage::redirect($_CMAPP['services_url']."/projects/project.php?frm_codProjeto=$_REQUEST[frm_codeProject]&frm_amerror=edit_not_allowed");
  }
}

//form box to interface
$cadBox = new AMTCadBox("",AMTCadBox::CADBOX_CREATE,AMTCadBox::PROJECT_THEME);

switch($_REQUEST['action']) {

 	default:
 		if(!empty($_FILES['frm_foto'])) {
 			$_SESSION['cad_foto'] = unserialize($_SESSION['cad_foto']);
	     	try {
    	   		$_SESSION['cad_foto']->loadImageFromRequest("frm_foto");
     		}catch(AMEImage $e) {
	       		header("Location:$_SERVER[PHP_SELF]?action=pag_2&frm_amerror=invalid_image_type");
    	 	}
   		}

	   	$view = $_SESSION['cad_foto']->getView();
   		$cadBox->add("<p align=center>");
   		$cadBox->add($view);
   
   		$_SESSION['cad_foto']=serialize($_SESSION['cad_foto']);

   		//get the image types that are allowed in this installation of gd+php
   		$types = AMImage::getValidImageExtensions();

   		$cadBox->add("<form name=cad_user method=post action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">");
   		$cadBox->add("<input type=hidden name=action value=pag_2>");
   		$cadBox->add("<p align=center class='texto'>".$_language['frm_foto']);
   		$cadBox->add("&nbsp;".$_language['valid_image_types']." ".implode(", ",$types).".");
   		$cadBox->add("<br /><input type=file name=frm_foto onChange=\"this.form.submit()\">");
   		$cadBox->add("<br /><input type=submit onClick=\"this.form['action'].value='pag_3'\" value=\"$_language[next]\">");
   		$cadBox->add("</form>");


   		$cadBox->setTitle("<img src='$_CMAPP[imlang_url]/img_foto_projeto_txt.gif'>");

   		break;
 	case "pag_3":
   
		$foto = unserialize($_SESSION['cad_foto']);
   		if($foto==false) $foto = $_SESSION['cad_foto'];

   		if(($foto->state==CMObj::STATE_DIRTY) || ($foto->state==CMObj::STATE_DIRTY_NEW)) {
	     	$foto->time = time();
    	 	try {
				$foto->save(); 
     		} catch(CMDBException $e) {
       			header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_picture");
     		}
     
     		$_SESSION['cad_proj']->image = (integer) $foto->codeFile;
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
   		unset($_SESSION['cad_foto']);
   
   		//if everything was ok, go the page of the project.
   		CMHTMLPage::redirect($_CMAPP['services_url'].'/projects/project.php?frm_ammsg=image_changed&frm_codProjeto='.$cod);
   
  		break;


 	case "fatal_error":
   		//No caso de um erro fatal.
   		//A mensagem de erro e exibida pelo proprio template AMMain.
   		$cadBox->add("<div align=center><a href=\"$_SERVER[PHP_SELF]\">$_language[try_again]</a></div>");
   		break;
}
   
$pag->add($cadBox);
echo $pag;