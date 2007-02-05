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
if(!empty($_REQUEST['frm_codeProjeto'])) {
  $_SESSION['cad_proj'] = new AMProject;
  $_SESSION['cad_proj']->codeProject = $_REQUEST['frm_codeProjeto'];
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

  $_SESSION['cad_foto'] = new AMProjectImage;
  $_SESSION['cad_foto']->codeFile = (integer) $_SESSION['cad_proj']->image;
  try {
    $_SESSION['cad_foto']->load();
  }  catch (CMDBNoRecord $e) {
    $_SESSION['cad_foto'] = new AMProjectImage;
  }  catch (CMDBQueryError $e) {
    $_SESSION['cad_foto'] = new AMProjectImage;
  }     
  if(!$group->isMember($_SESSION['user']->codeUser)) {
    CMHTMLPage::redirect($_CMAPP['services_url']."/projects/project.php?frm_codProjeto=$_REQUEST[frm_codeProjeto]&frm_amerror=edit_not_allowed");
  }
}

$el['default'] = $_language['pag_0'];
$el['pag_1'] = $_language['pag_1'];
$el['pag_2'] = $_language['pag_2'];
$el['pag_3'] = $_language['pag_3'];
$ind =  new AMPathIndicator($el);

(!isset($_REQUEST['action']) ? $_REQUEST['action']="" : '');

$ind->setState($_REQUEST['action']);
$pag->setPathIndicator($ind);


//form box to interface
$cadBox = new AMTCadBox("",AMTCadBox::CADBOX_CREATE,AMTCadBox::PROJECT_THEME);

switch($_REQUEST['action']) {

 default:

   $fields_rec = array("title","description");
      
   //formulary
   $form = new AMWSmartForm('AMProject',"cad_user",$_SERVER['PHP_SELF'],$fields_rec);
   
   $form->setCancelUrl("$_CMAPP[services_url]/projects/projects.php?clear_cadProj");

   if(isset($_SESSION['cad_proj']) && ($_SESSION['cad_proj'] instanceof CMObj)) {  	
     $form->loadDataFromObject($_SESSION['cad_proj']);
   } else { 
     $_SESSION['cad_proj']='';
   }
   
   //$status = AMProject::listAvaiableStatus();
   
   $form->addComponent("action",new CMWHidden("action","pag_1"));

   $cadBox->add($form);
   $cadBox->setTitle("<img src='$_CMAPP[imlang_url]/img_dados_gerais.gif'>");

   break;
      
 case "pag_1":

   if((!($_SESSION['cad_proj'] instanceof AMProject))||($_SESSION['cad_proj']->state==CMObj::STATE_NEW) || ($_REQUEST['frm_title']!=$_SESSION['cad_proj']->title)) {
     $proj = new AMProject;
     $proj->title = $_REQUEST['frm_title'];
     try {
       $proj->load();

       $_SESSION['cad_proj'] = new AMProject;
       $_SESSION['cad_proj']->loadDataFromRequest();
       
       header("Location:$_SERVER[PHP_SELF]?frm_amerror=proj_exists");
     }catch (CMDBNoRecord $e) {
       unset($proj);
     }   
   }

   $form = new AMWSmartForm('AMProject',"select_areas",$_SERVER['PHP_SELF']);

   if(!($_SESSION['cad_proj'] instanceof AMProject)) {
     //if this is the first submit, create an object in the session to store the user data
     $_SESSION['cad_proj'] = new AMProject();
     $_SESSION['cad_proj']->loadDataFromRequest();
     $_SESSION['cad_proj']->time = time();
   }
   else {
     //if the user hit back, fill the from with the data from the session object
     $_SESSION['cad_proj']->loadDataFromRequest();
     $form->loadDataFromObject($_SESSION['cad_proj']);
   }


   $areas = $_SESSION['environment']->listAreas();
   $form->addComponent("acao", new CMWHidden("action","pag_2"));

   if($_SESSION['cad_proj'] instanceof CMObj) {
     if($_SESSION['cad_proj']->state!=CMObj::STATE_DIRTY_NEW && $_SESSION['cad_proj']->state!=CMObj::STATE_NEW) {
       try {
	 $proj_areas = $_SESSION['cad_proj']->listAreas();
	 $areas->sub($proj_areas);
       }
       catch(CMObjEPropertieNotDefined $e) {
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
 
   //image stuff
   if(!($_SESSION['cad_foto'] instanceof AMProjectImage)){
     $_SESSION['cad_foto'] = new AMProjectImage;
   }

   if(!empty($_FILES['frm_foto'])) {
     try {
       $_SESSION['cad_foto']->loadImageFromRequest("frm_foto");
     }
     catch(AMEImage $e) {
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
   $cadBox->add("<br><input type=file name=frm_foto onChange=\"this.form.submit()\">");
   $cadBox->add("<br><input type=submit onClick=\"this.form['action'].value='pag_3'\" value=\"$_language[next]\">");
   $cadBox->add("</form>");


   $cadBox->setTitle("<img src='$_CMAPP[imlang_url]/img_foto_projeto_txt.gif'>");

   break;
 case "pag_3":
   
	$foto = unserialize($_SESSION['cad_foto']);
   	if($foto==false) $foto = $_SESSION['cad_foto'];

   	if(($foto->state==CMObj::STATE_DIRTY) || ($foto->state==CMObj::STATE_DIRTY_NEW)) {
     	$foto->time = time();
     	try {
     		die(note($foto));
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
   	};


   	//forces the ins of the user in the group
   	$member = new CMGroupMember;
   	$member->codeGroup = $_SESSION['cad_proj']->codeGroup;
   	$member->codeUser = $_SESSION['user']->codeUser;
   	$member->time = time();
   	$con->add($max+1,$member);

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
   	CMHTMLPage::redirect($_CMAPP['services_url'].'/projects/project.php?frm_ammsg=project_created&frm_codProjeto='.$cod);
   
  	break;


 case "fatal_error":
   //No caso de um erro fatal.
   //A mensagem de erro e exibida pelo proprio template AMMain.
   $cadBox->add("<div align=center><a href=\"$_SERVER[PHP_SELF]\">$_language[try_again]</a></div>");
   break;
}
   
$pag->add($cadBox);
echo $pag;