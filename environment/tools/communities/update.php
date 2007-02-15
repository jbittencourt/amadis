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
//if there is a community alredy in the session, and you
//are trying to edit other community, this means that in
//the last time, the final user don't completed the
//process. So I must unset the session community
//to force the script to load a new one.
if($_SESSION['updating_community'] instanceof AMCommunities) {
	if($_SESSION['updating_community']->code!=$_REQUEST['frm_codeCommunity']) {
		unset($_SESSION['updating_community']);
	}
}

/*
 *Load language module
 */
if(!($_SESSION['updating_community'] instanceof AMCommunities)){
	$_SESSION['updating_community'] = new AMCommunities;
	$_SESSION['updating_community']->code = $_REQUEST['frm_codeCommunity'];
	try{
		$_SESSION['updating_community']->load();
	}catch(CMDBNoRecord $e) {
		unset($_SESSION['updating_community']);
		$location  = $_CMAPP['services_url']."/communities/communities.php?frm_amerror=community_not_exists";
		$location .= "&frm_codProjeto=".$_REQUEST['frm_codProjeto'];
		CMHTMLPage::redirect($location);
	}

	$temp = $_SESSION['updating_community']->image;
	$_SESSION['cad_image'] = new AMCommunityImage;

	if(!empty($temp)) {

		$_SESSION['cad_image']->codeFile = (integer) $_SESSION['updating_community']->image;
		$_SESSION['cad_image']->load();
	};
}



//form box to interface
$cadBox = new AMTCommunityCadBox("", AMTCadBox::COMMUNITY_THEME);

switch($_REQUEST['action']) {

 	default:

	 	$conteudo = "<form method=post action=\"$_SERVER[PHP_SELF]\">";
 		$conteudo.= "<input type=hidden name=action value=pag_1><br>";
 		$conteudo.= "<input type=hidden name=frm_codeCommunity value=$_REQUEST[frm_codeCommunity]>";
 		$conteudo.= "<input type=hidden name=frm_artificio value='yes'>";
 		$conteudo.= "<table cellpadding='2' cellspacing='1' width='70%'><tbody><tr><td></td></tr><tr><td>";
 		$conteudo.= $_language['frm_name'];
 		$conteudo.= "<br><input name='frm_name' id='frm_name' value='".$_SESSION['updating_community']->name."' size='30' maxlength='30' type='text'>";
 		$conteudo.= "</td></tr><tr><td>";
 		$conteudo.= $_language['frm_description'];
 		$conteudo.= "<br><textarea name='frm_description' id='frm_description' rows='5' cols='35'>".$_SESSION['updating_community']->description."</textarea>";
 		$conteudo.= "</td></tr><tr><td>";

 		if($_SESSION['updating_community']->flagAuth=='ALLOW') {
 			$flaAuthAllow="checked";
 		} else {
 			$flaAuthRequest="checked";
 		}
 		$conteudo.= "<br>".$_language['public']."&nbsp;<input name='frm_flagAuth' id='frm_flagAuth' value='ALLOW' $flaAuthAllow type='radio'>";
 		$conteudo.= "<br>".$_language['moderate']."&nbsp;<input name='frm_flagAuth' id='frm_flagAuth' value='REQUEST' $flaAuthRequest type='radio'>";

 		$conteudo.= "</td></tr><tr><td><table align='right'><tbody><tr><td>";
 		$conteudo.= "&nbsp;<input name='cancelar' id='cancelar' value='".$_language['cancel']."' type='button'>";
	 	$conteudo.= "</td><td>";
 		$conteudo.= "&nbsp;<input name='eviar' id='eviar' value='".$_language['send']."' type='submit'>";
	 	$conteudo.= "</td></tr></tbody></table></td></tr></tbody></table>";
 		$conteudo.= "</form>";

 		$cadBox->add($conteudo);
 		$cadBox->setTitle($_language['general_data']);
  
 		break;

 	case "pag_1":
	 	$_SESSION['updating_community']->loadDataFromRequest();

	 	//save the community
 		if($_SESSION['updating_community']->state==CMObj::STATE_DIRTY) {
 			try {
 				$_SESSION['updating_community']->save();
 			} catch(CMDBException $e) {
	 			header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_user");
 			} catch(AMException $e) {
 				header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=creating_user_dir");
 			}
 		}

 		$cod = $_SESSION['updating_community']->code;
 		unset($_SESSION['updating_community']);
 		//if everything was ok, go the page of the project.

 		header("Location: $_CMAPP[services_url]/communities/community.php?frm_codeCommunity=$cod&frm_ammsg=community_updated");
  
	 	break;

	case "fatal_error":
 		//No caso de um erro fatal.
 		//A mensagem de erro e exibida pelo proprio template AMMain.
 		$cadBox->add("<div align=center><a href=\"$_SERVER[PHP_SELF]\">$_language[try_again]</a></div>");
 		break;
}
 
$pag->add($cadBox);
echo $pag;