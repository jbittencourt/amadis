<?php
include('../../config.inc.php');
$_language = $_CMAPP['i18n']->getTranslationArray("wiki");
$page = new AMTProjeto();

if(isset($_REQUEST['frm_namespace']) && !empty($_REQUEST['frm_namespace'])) {
	$wikiPage = new AMWikiPage;
	$wikiPage->namespace = $_REQUEST['frm_namespace'];
	$wikiPage->title = empty($_REQUEST['frm_title']) ? "Main_Page" : $_REQUEST['frm_title'];
	try {
		$wikiPage->load();
	}catch(CMException $e) {
		$wikiPage->save();
	}
}

if(empty($wikiPage->text) && empty($_REQUEST['frm_title']) && $_REQUEST['frm_title'] != 'Main_Page') {
	$wikiPage->text = "\n\nMain Page\n==========\n\nEsta é uma pagina para voce porder organizar todo o seu conteudo de pesquisa de seu projeto\n";
} else if(empty($wikiPage->text)) {
	$wikiPage->text = "\n\n".$wikiPage->title."\n=======\n\n";
}

AMMain::addXOADHandler('AMWiki', 'AMWiki');

$page->requires('jsCrossmark.css', CMHTMLObj::MEDIA_CSS);
$page->requires('jsCrossmark.js', CMHTMLObj::MEDIA_JS);
$page->requires('wiki.js', CMHTMLObj::MEDIA_JS);

$page->addPageBegin(CMHTMLObj::getScript("var CURRENT_NAMESPACE = '$_REQUEST[frm_namespace]'"));
$page->addPageBegin(CMHTMLObj::getScript("var CURRENT_PAGE = '$wikiPage->title'"));

$page->add('<input type="button" value="Edit" id="edit" onclick="javascript:toggleEdit();"/>');

$page->add('<textarea style="display: none;" id="'.$wikiPage->title.'">'."\n".$wikiPage->text.'</textarea>');
$page->add('<textarea id="txtarea" cols=85 rows=30></textarea>');
$page->add('<div id="result"></div>');

$page->add(CMHTMLObj::getScript('wikiLoad("'.$wikiPage->title.'");'));

echo $page;

?>