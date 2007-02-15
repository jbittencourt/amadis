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

$_language = $_CMAPP['i18n']->getTranslationArray("community_create");


$pag = new AMTEditCommunity();


if(empty($_REQUEST['frm_codeCommunity'])) {
	$pag->addError($_languge['error_community_not_exists'], '');
	$_REQUEST['action'] = 'fatal_error';
} else {
	$community = new AMCommunities;
	$community->code = (integer) $_REQUEST['frm_codeCommunity'];
	try{
		$community->load();
	}catch(CMDBNoRecord $e) {
		$location  = $_CMAPP['services_url']."/communities/communities.php?frm_amerror=community_not_exists";
		CMHTMLPage::redirect($location);
	}

	if(empty($_SESSION['cad_image'])) {
		$_SESSION['cad_image'] = new AMCommunityImage;
		$_SESSION['cad_image']->codeFile = (integer) $community->image;
		try {
			$_SESSION['cad_image']->load();
		}catch(CMException $e) {
			new AMErrorReport($e, 'AMCommunityImage::load', AMLog::LOG_COMMUNITIES);
			$_SESSION['cad_image'] = new AMCommunityImage;
		}
	} else {
		$_SESSION['cad_image'] = unserialize($_SESSION['cad_image']);
	}

}

//form box to interface
$cadBox = new AMTCommunityCadBox("", AMTCadBox::COMMUNITY_THEME);

switch($_REQUEST['action']) {

 default:
	
 	//image stufff
 	if(!empty($_FILES['frm_image'])) {
 		try {
 			$_SESSION['cad_image']->loadImageFromRequest("frm_image");
 		} catch(AMEImage $e) {
 			header("Location:$_SERVER[PHP_SELF]?action=pag_1&frm_amerror=invalid_image_type");
 		}
 	};

 	$view = $_SESSION['cad_image']->getView();
 	$cadBox->add("<p align=center>");
 	$cadBox->add($view);
  
 	$_SESSION['cad_image']=serialize($_SESSION['cad_image']);
  
 	//get the image types that are allowed in this installation of gd+php
 	$types = AMImage::getValidImageExtensions();

 	$cadBox->add("<form name=cad_user method=post action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">");
 	$cadBox->add("<input type=hidden name=action value=pag_1>");
 	$cadBox->add("<input type=hidden name='frm_codeCommunity' value='$_REQUEST[frm_codeCommunity]'>");
 	$cadBox->add("<p align=center>".$_language['frm_image']);
 	$cadBox->add("&nbsp;".$_language['valid_image_types']." ".implode(", ",$types).".");
 	$cadBox->add("<br><input type=file name=frm_image onChange=\"this.form.submit()\">");
 	$cadBox->add("<br><input type=submit onClick=\"this.form['action'].value='pag_2'\" value=\"$_language[next]\">");
 	$cadBox->add("</form>");

 	$cadBox->setTitle($_language['community_pic']);

 	break;

 case "pag_2":
	
 	$foto = $_SESSION['cad_image'];
 	if(($foto->state==CMObj::STATE_DIRTY) || ($foto->state==CMObj::STATE_DIRTY_NEW)) {
 		$foto->time = time();
 		try {
 			$foto->save();

 			if($community->image == 0 || empty($community->image)) {
 				$community->image = (integer) $foto->codeFile;
 				$community->state = CMObj::STATE_DIRTY;
 			}
 		}
 		catch(CMDBException $e) {
 			header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_picture");
 		}
 	}
 	 
 	//if arrives here, we havnt changes in the object

 	//save the community
 	if($community->state == CMObj::STATE_DIRTY || $community->state == CMObj::STATE_DIRTY_NEW) {
 		try {
 			$community->save();
 		}
 		catch(CMDBException $e) {
 			if($foto->state==CMObj::STATE_PERSISTENT) {
 				$foto->delete();
 			}
 			header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_user");
 		}
 		catch(AMException $e) {
 			if($foto->state == CMObj::STATE_PERSISTENT) {
 				$foto->delete();
 			}
 			header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=creating_user_dir");
 		}
 	}

 	unset($_SESSION['cad_community']);
 	//if everything was ok, go the page of the project.

 	header("Location: $_CMAPP[services_url]/communities/community.php?frm_codeCommunity=".$community->code."&frm_ammsg=community_updated");
  
 	break;

 	case "fatal_error":
 		//No caso de um erro fatal.
 		//A mensagem de erro e exibida pelo proprio template AMMain.
 		$cadBox->add("<div align=center><a href=\"$_SERVER[PHP_SELF]\">$_language[try_again]</a></div>");
 		break;
}
 
$pag->add($cadBox);
echo $pag;