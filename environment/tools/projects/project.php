<?php
$_CMAPP['notrestricted'] = 1;
include("../../config.inc.php");


$_language = $_CMAPP['i18n']->getTranslationArray("projects");

$pag = new AMTProjeto;
$box = new AMTwoColsLayout;


if(isset($_REQUEST['frm_codProjeto']) && !empty($_REQUEST['frm_codProjeto'])) {
	$proj = new AMProject;
	$proj->codeProject = $_REQUEST['frm_codProjeto'];
	try{
		$proj->load();
		$group = $proj->getGroup();
	}catch(CMDBNoRecord $e){
		$location  = $_CMAPP['services_url'].'/projects/project.php?frm_amerror=project_not_exists'
				   . '&frm_codProjeto='.$_REQUEST['frm_codProjeto'];
		CMHTMLPage::redirect($location);
	}
} else {
	$_REQUEST['frm_amerror'] = 'any_project_id';

	$pag->add('<br /><div align="center"><a href="'.$_SERVER['HTTP_REFERER'].'" ');
	$pag->add('class="cinza">'.$_language['back'].'</a></div><br />');
	echo $pag;
	die();
}


//checks if the user is a member of the project
$isMember = false;
if(!empty($_SESSION['user'])) {
	$isMember = $group->isMember($_SESSION['user']->codeUser);
	if(!$isMember) $proj->hit();
}
$_CMAPP['smartform']['language'] = $_language;

if($isMember) {
	$req = new AMBProjectRequest($proj);
	if($req->hasRequests()) {
		$pag->add($req);
	}
}

$proj_description = nl2br($proj->description);

/*
 *INICIO DA PAGINA
 */

//coluna da esquerda
$box->add('<span class="project-title">'.$_language['project'].': '.$proj->title.'</span><br />', AMTwoColsLayout::LEFT);
$box->add('<img src='.$_CMAPP['images_url'].'/dot.gif" border="0" height="10" width="1"><br />', AMTwoColsLayout::LEFT);

$imageCode = AMProjectImage::getImage($proj);
$image = new AMProjectImage();
try {
	$image->codeFile = $imageCode;
	try {
		$image->load();
		$box->add($image->getView(), AMTwoColsLayout::LEFT);
	} catch(CMDBNoRecord $e) {
		echo $e;
	}	
}catch (CMObjEPropertieValueNotValid $e) {
	$box->add(new AMTProjectImage(AMProjectImage::DEFAULT_IMAGE, AMImageTemplate::METHOD_DEFAULT), 
	AMTwoColsLayout::LEFT);
}

$box->add('      <br />', AMTwoColsLayout::LEFT);
$box->add('<img src="'.$_CMAPP['images_url'].'/dot.gif" border="0" height="10" width="1"><br />', AMTwoColsLayout::LEFT);

$box->add('<span class="texto">'.$_language['project'].': '.$proj->title.' <br />'.$proj_description.'</span><br />', AMTwoColsLayout::LEFT);
$box->add('<img src="'.$_CMAPP['images_url'].'/dot.gif" border="0" height="10" width="1"><br />', AMTwoColsLayout::LEFT);

/*
 *STATUS DO PROJETO
 * I disabled the project status. It has no
 * significance to the users of amadis
 */
$box->add('    <img src="'.$_CMAPP['imlang_url'].'/img_dados_projeto.gif"><br />', AMTwoColsLayout::LEFT);
$box->add('    <img src="'.$_CMAPP['images_url'].'/dot.gif" border="0" height="10" width="1"><br />', AMTwoColsLayout::LEFT);

/*
 *AREAS
 */
$projAreas = $proj->listAreas();

$box->add('<b>'.$_language['project_areas'].'</b>',AMTwoColsLayout::LEFT);
if($projAreas->__hasItems()) {
	$areas = array();
	foreach ($projAreas as $item) {
		$areas[] = $item->name;
	}
	$box->add(' '.implode(', ',$areas).'.',AMTwoColsLayout::LEFT);
	$box->add('<br /><br />',AMTwoColsLayout::LEFT);
}
else {
	$box->add($_language['any_area'].'<br /><br />',AMTwoColsLayout::LEFT);
}

/*
 *EQUIPE DO PROJETO
 */
$box->add(new AMBProjectGroup($proj),AMTwoColsLayout::LEFT);
// END of the members box.


/*
 *CADASTRO DE NOTICIAS
 */

$projNews = new AMBProjectNews($proj);
$box->add($projNews,AMTwoColsLayout::LEFT);


/**
 *FINAL DA COLUNA ESQUERDA
 **/

$projItens = new AMBProjectItems;
$box->add($projItens,AMTwoColsLayout::RIGHT);

$box->add('<img src="'.$_CMAPP['images_url'].'/dot.gif" width="20" height="1" border="0">', AMTwoColsLayout::RIGHT);

/*
 *COLUNA DIREITA
 */
 /*
  *CAIXA DE EDICAO DO PROJETO
  */
 if(!isset($orfan)){
 	$orfan = "";
 }

 if(!$orfan) {
 	if($_SESSION['user'] instanceof CMUser) {
 		if($isMember) {
 			$projEdit = new AMBProjectEdit($proj);
 			$box->add($projEdit, AMTwoColsLayout::RIGHT);
 		}
 		else {
 			$box->add(new AMBProjectJoin($proj), AMTwoColsLayout::RIGHT);
 		}
 		$box->add('<br />', AMTwoColsLayout::RIGHT);
 	}
 }

 /*
  * CAIXA DA BIBLIOTECA DO PROJETO
  */


 if($_SESSION['user'] instanceof CMUser) {
 	if($isMember) {
 		$checkIfExist = new AMProjectLibraryEntry($proj->codeProject);
 		$checkIfExist->libraryExist();
 		$projLibrary = new AMBProjLibrary($proj);
 		$box->add($projLibrary, AMTwoColsLayout::RIGHT);
 	}
 	else{
 		$shared = new AMBProjLibraryShare($proj,5,1);
 		$box->add($shared, AMTwoColsLayout::RIGHT);
 	}
 	$box->add('<br />', AMTwoColsLayout::RIGHT);
 }

 // fim lib projeto

 $projComents = new AMBProjectComents;
 $box->add($projComents,AMTwoColsLayout::RIGHT);

 $box->add('<br />', AMTwoColsLayout::RIGHT);


 $pag->add($box);
 echo $pag;