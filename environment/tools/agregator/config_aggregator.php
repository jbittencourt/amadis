<?php
include('../../config.inc.php');

$_language = $_CMAPP['i18n']->getTranslationArray("projects");

$pag = new AMTAgregator;

if(array_key_exists('frm_codeProject', $_REQUEST) && !empty($_REQUEST['frm_codeProject'])) {
	$proj = new AMProject;
	$proj->codeProject = $_REQUEST['frm_codeProject'];
	try{
		$proj->load();
		$group = $proj->getGroup();
	}catch(CMDBNoRecord $e){
		$location  = $_CMAPP['services_url']."/projects/project.php?frm_amerror=project_not_exists";
		$location .= "&frm_codProjeto=".$_REQUEST['frm_codeProject'];
		header("Location:$location");
	}
} else {
	$_REQUEST['frm_amerror'] = "any_project_id";

	$pag->add("<br /><div align=center><a href=\"".$_SERVER['HTTP_REFERER']."\" ");
	$pag->add("class=\"cinza\">".$_language['back']."</a></div><br />");
	echo $pag;
	die();
}

$isMember = false;
if(!empty($_SESSION['user'])) {
	$isMember = $group->isMember($_SESSION['user']->codeUser);
	if(!$isMember) $proj->hit();
}


$box = new AMBAggregator($proj->codeProject);

if(empty($proj->image)) $box->setThumb(new AMTProjectImage(AMProjectImage::DEFAULT_IMAGE, AMTProjectImage::METHOD_DEFAULT));
else $box->setThumb(new AMTProjectImage($proj->image));

$box->setTitle($proj->title);


$group = $proj->getGroup();

$projMembers = $group->listActiveMembers();
$sources = AMAgregatorFacade::getSources($_REQUEST['frm_codeProject']);

if($sources->__hasItems()) {
	foreach($sources as $item) {
		if(isset($projMembers->items[$item->codeUser])) {
			unset($projMembers->items[$item->codeUser]);
		}
	}
}


if($projMembers->__hasItems()) {
	$con = new CMContainer;
	
	foreach($projMembers as $member) {
		$src = new AMProjectBlogs;
		$src->codeUser = $member->codeUser;
		$src->title = $member->name.' ('.$member->username.')';
		$src->codeProject = $proj->codeProject;
		
		$con->add($member->codeUser, $src);
	}

	try {
		$con->acidOperation(CMContainer::OPERATION_SAVE);
	} catch(CMObjEContainerOperationFailed $e) {
		new AMErrorReport($e, 'AMAggregator::saveSources', AMLog::LOG_AGGREGATOR);
		$pag->addError($_language['same_errors_with_sources'], $e);
	}
}

$sources = AMAgregatorFacade::getSources($_REQUEST['frm_codeProject']);

$box->addSources($sources);
$filter = new AMAgregator;
$filter->codeAggregator = $proj->codeProject;
try {
	$filter->load();
	$box->setFilter($filter->keywords);
}catch(CMException $e) {}

$pag->add($box);

echo $pag;
?>