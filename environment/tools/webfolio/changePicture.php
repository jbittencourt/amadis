<?php
/**
 * This file changes the picture of an user.
 *
 * LICENSE: Licensed under GPL
 *
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    $Id$
 * @since      File available since Release 1.2.0
 * @author     Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("changeUserData");


$pag = new AMTCadastro();
$cadBox = new AMTCadBox("",AMTCadBox::CADBOX_CREATE,AMTCadBox::WEBFOLIO_THEME);

/*
 *Load language module
 */

$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;

$action = AMEnvironment::processActionRequest();
switch($action) {

	default:
		if(empty($action)) {
			$createNewImage = false;

			$_SESSION['cad_user'] = clone $_SESSION['user'];
			$_SESSION['cad_foto'] = new AMUserPicture();
			//$_SESSION['cad_foto']->codeFile = AMUserPicture::getImage($_SESSION['user']);
			$codeFile = AMUserPicture::getImage($_SESSION['user']);
			//test if the returned image is the default image
			if($codeFile == AMUserPicture::DEFAULT_IMAGE ) {
				$createNewImage = true;
			}else {
				$_SESSION['cad_foto']->codeFile = $codeFile;
				try {
					$_SESSION['cad_foto']->load();
				} catch(CMDBNoRecord $e) {
					$createNewImage = true;
				}
			}
				
			if($createNewImage) {
				$_SESSION['cad_foto'] = new AMUserPicture();
				$_SESSION['cad_user']->picture = 0;
			};

		}

		if(isset($_FILES['frm_foto']) && !empty($_FILES['frm_foto'])) {
			$ustemp= @unserialize($_SESSION['cad_foto']);
			if($ustemp!=false) $_SESSION['cad_foto']=$ustemp;

			try {
				
				echo $_SESSION['amadis']['old_photo_name'] = $_SESSION['cad_foto']->name;
				$_SESSION['cad_foto']->loadImageFromRequest("frm_foto");
			}
			catch(AMEImage $e) {
				header("Location:$_SERVER[PHP_SELF]?action=pag_2&frm_amerror=invalid_image_type");
			}

			$view = $_SESSION['cad_foto']->getView();

		}
		else {
			$foto = @unserialize($_SESSION['cad_foto']);
			if($foto===false) $foto = $_SESSION['cad_foto'];
			$view = $foto->getView();
		}

		$cadBox->add("<p align=center>");
		$cadBox->add($view);


		//get the image types that are allowed in this installation of gd+php
		$types = AMImage::getValidImageExtensions();

		$cadBox->add("<form name=cad_user method=post action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">");
		$cadBox->add("<input type=hidden name=action value=pag_1>");
		$cadBox->add("<p align=center>".$_language['frm_foto']);
		$cadBox->add("&nbsp;".$_language['valid_image_types']." ".implode(", ",$types).".");
		$cadBox->add("<br><input type=file name=frm_foto onChange=\"this.form.submit()\">");
		$cadBox->add("<br><input type=submit onClick=\"this.form['action'].value='pag_2'\" value=\"$_language[finish]\">");
		$cadBox->add("</form>");

		$cadBox->setTitle($_language['pag_1']);


		$_SESSION['cad_foto']=serialize($_SESSION['cad_foto']);
		//$_SESSION['cad_user']=serialize($_SESSION['cad_user']);
		break;
	case "pag_2":

			
		if(empty($_SESSION['cad_foto'])) {
			header("Location:$_SERVER[PHP_SELF]?action=pag_2&frm_amerror=picture_not_defined");
		}


		$foto = unserialize($_SESSION['cad_foto']);
		if($foto===false) {
			$foto = $_SESSION['cad_foto'];
		}

		$oldState = $foto->state;

		if($foto->state==CMObj::STATE_DIRTY || $foto->state==CMObj::STATE_DIRTY_NEW) {
			try {
				$foto->save();
				$_SESSION['cad_user']->picture = $foto->codeFile;
			}
			catch(CMDBException $e) {
				header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_picture");
			}
		}

		//only saves de user profile if the image is new. If it was updated, the codeFile remains the same
		//so no modifications is needed.
		if($oldState == CMObj::STATE_DIRTY_NEW || $oldState==CMObj::STATE_DIRTY) {
			
			try {
				$_SESSION['cad_user']->save();
			}
			catch(CMDBException $e) {
				header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_user");
			}
			$_SESSION['user'] = $_SESSION['cad_user'];
		}
		unset($_SESSION['cad_user']);
		unset($_SESSION['cad_foto']);

		header("Location:$_CMAPP[services_url]/webfolio/webfolio.php?frm_ammsg=picture_changed");
		$cadBox->setTitle($_language['pag_2']);

		break;
	case "fatal_error":
		//No caso de um erro fatal.
		//A mensagem de erro e exibida pelo proprio template AMMain.
		$cadBox->add("<div align=center><a href=\"$_SERVER[PHP_SELF]\">$_language[try_again]</a></div>");
		break;
}


$pag->add($cadBox);
echo $pag;