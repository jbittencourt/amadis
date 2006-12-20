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


$pag = new AMTCadCommunity();

/*
 *Load language module
 */

$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;


$el["default"] = $_language['pag_0'];
$el['pag_1'] = $_language['pag_1'];
$el['pag_2'] = $_language['pag_2'];
$ind =  new AMPathIndicator($el);
$ind->setState($_REQUEST['action']);
$pag->setPathIndicator($ind);


//form box to interface
$cadBox = new AMTCommunityCadBox("", AMTCadBox::COMMUNITY_THEME);

unset($_REQUEST['frm_codeCommunity']);

switch($_REQUEST['action']) {

	default:

		$fields_rec = array("name","description","flagAuth");

		//formulary
		$form = new AMWSmartForm('AMCommunities',"cad_community",$_SERVER['PHP_SELF'],$fields_rec);

		$form->setWidgetOrder($fields_rec);

		if($_SESSION['cad_community'] instanceof CMObj) {
			$form->loadDataFromObject($_SESSION['cad_community']);
		}
			
		$flagAuth = array(AMCommunities::ENUM_FLAGAUTH_ALLOW=>"$_language[public]",
		AMCommunities::ENUM_FLAGAUTH_REQUEST=>"$_language[moderate]"
		);

		$form->setRadioGroup("flagAuth",$flagAuth);
		$form->components['flagAuth']->setValue(AMCommunities::ENUM_FLAGAUTH_ALLOW);

		$form->addComponent("action",new CMWHidden("action","pag_1"));

		$descrip = new CMWTextArea("frm_description", 5, 35);
		$form->addComponent("description",$descrip);

		$cadBox->add($form);
		$cadBox->setTitle($_language['general_data']);

		break;

	case "pag_1":

		if(!empty($_REQUEST['frm_name'])) {
			$community = new AMCommunities;
			$community->name = $_REQUEST['frm_name'];
			try {
				$community->load();
				$_SESSION['cad_community'] = new AMCommunities;
				$_SESSION['cad_community']->loadDataFromRequest();
				header("Location:$_SERVER[PHP_SELF]?frm_amerror=community_exists");
			}catch (CMDBNoRecord $e) {
				unset($community);
			}
		}

		if(!($_SESSION['cad_community'] instanceof AMCommunities)) {
			//if this is the first submit, create an object in the session to store the user data
			$_SESSION['cad_community'] = new AMCommunities();
			$_SESSION['cad_community']->loadDataFromRequest();
			$_SESSION['cad_community']->time = time();
		}
		else {
			//if the user hit back, fill the from with the data from the session object
			$_SESSION['cad_community']->loadDataFromRequest();
		}
		//image stufff
		$_SESSION['cad_image'] = new AMCommunityImage;
		if(!empty($_FILES['frm_image'])) {
			try {
				$_SESSION['cad_image']->loadImageFromRequest("frm_image");
			}
			catch(AMEImage $e) {
				header("Location:$_SERVER[PHP_SELF]?action=pag_1&frm_amerror=invalid_image_type");
			}
		}
		$view = $_SESSION['cad_image']->getView();
		$cadBox->add("<p align=center>");
		$cadBox->add($view);

		$_SESSION['cad_image']=serialize($_SESSION['cad_image']);


		//get the image types that are allowed in this installation of gd+php
		$types = AMImage::getValidImageExtensions();

		$cadBox->add("<form name=cad_user method=post action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">");
		$cadBox->add("<input type=hidden name=action value=pag_1>");
		$cadBox->add("<p align=center>".$_language['frm_image']);
		$cadBox->add("&nbsp;".$_language['valid_image_types']." ".implode(", ",$types).".");
		$cadBox->add("<br><input type=file name=frm_image onChange=\"this.form.submit()\">");
		$cadBox->add("<br><input type=submit onClick=\"this.form['action'].value='pag_2'\" value=\"$_language[next]\">");
		$cadBox->add("</form>");

		$cadBox->setTitle($_language['community_pic']);

		break;

	case "pag_2":

		$foto = unserialize($_SESSION['cad_image']);

		if($foto==false) $foto = $_SESSION['cad_image'];


		if(($foto->state==CMObj::STATE_DIRTY) || ($foto->state==CMObj::STATE_DIRTY_NEW)) {
			$foto->time = time();
			try {
				$foto->save();
			}
			catch(CMDBException $e) {
				header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_picture");
			}
			$_SESSION['cad_community']->image = (integer) $foto->codeFile;
		}

		//save the community
		try {
			$_SESSION['cad_community']->save();
		}
		catch(CMDBException $e) {
			if(!empty($foto)) {
				$foto->delete();
			}
			header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_user");
		}
		catch(AMException $e) {
			if(!empty($foto)) {
				$foto->delete();
			}
			echo $e->getMessage();
			header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=creating_user_dir");
		}
		
		$cod = $_SESSION['cad_community']->code;

		unset($_SESSION['cad_community']);
		unset($_SESSION['cad_image']);
		unset($_SESSION['amadis']['communities']);

		//if everything was ok, go the page of the community.

		CMHTMLPage::redirect($_CMAPP['services_url']."/communities/community.php?frm_codeCommunity=$cod&frm_ammsg=community_created");

		break;

	case "fatal_error":
		//No caso de um erro fatal.
		//A mensagem de erro e exibida pelo proprio template AMMain.
		$cadBox->add("<div align=center><a href=\"$_SERVER[PHP_SELF]\">$_language[try_again]</a></div>");
		break;
}

$pag->add($cadBox);
echo $pag;