<?php
include('../../config.inc.php');
$_language = $_CMAPP['i18n']->getTranslationArray("wiki");

/*$page = new AMTProjeto();

if(isset($_REQUEST['frm_namespace']) && !empty($_REQUEST['frm_namespace'])) {
	$wikiPage = new AMWikiPage;
	$wikiPage->namespace = $_REQUEST['frm_namespace'];
	$wikiPage->title = empty($_REQUEST['frm_title']) ? "Main_Page" : $_REQUEST['frm_title'];
	try {
		$wikiPage->load();
	}catch(CMException $e) {
		$wikiPage->save();
	}
	if(isset($_REQUEST['frm_revision']) && !empty($_REQUEST['frm_revision'])) {
		$wikiPage->getOldRevision($_REQUEST['frm_revision']);
	}
}
*/
note($_REQUEST);
?>