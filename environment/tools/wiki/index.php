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
	$wikiPage->text = "\nMain Page\n============\n\nEsta é uma pagina para voce porder organizar todo o seu conteudo de pesquisa de seu projeto\n";
} else if(empty($wikiPage->text)) {
	$wikiPage->text = "\n\n".str_replace('_', ' ', $wikiPage->title)."\n".str_repeat("=", strlen($wikiPage->title))."\n\n";
}

AMMain::addXOADHandler('AMWiki', 'AMWiki');

$page->requires('jsCrossmark.css', CMHTMLObj::MEDIA_CSS);
$page->requires('jsCrossmark.js', CMHTMLObj::MEDIA_JS);
$page->requires('wiki.js', CMHTMLObj::MEDIA_JS);

$page->addPageBegin(CMHTMLObj::getScript("var CURRENT_NAMESPACE = '$_REQUEST[frm_namespace]'"));
$page->addPageBegin(CMHTMLObj::getScript("var CURRENT_PAGE = '$wikiPage->title'"));

/**
 * Action bar
 */
$page->add('<ul id="jsCrossmark_actionBar">
	<li><a href="index.php?frm_namespace='.$wikiPage->namespace.'&frm_title='.$wikiPage->title.'">Artigo</a></li>
	<li>Discusão</li>
	<li><a href="javascript:void(0);" onclick="javascript:toggleEdit();">Editar</a></li>
	<li><a href="history.php?frm_namespace='.$wikiPage->namespace.'&frm_title='.$wikiPage->title.'">História</a></li>
	<li>Mover</li>
	<li>Vigiar</li>
</ul>');

$page->add('<div id="preview_result"></div>');
$page->add('<div id="result"></div>');

/**
 * Edition Area
 */
$page->add('<div id="jsCrossMark_editArea" style="display:none;">');

/**
 * Wiki editition toolbar
 */
$page->add('<div id="edit_toolbar">EDIT_TOOLBAR</div>');

$page->add('<textarea style="display: none;" id="'.$wikiPage->title.'">'."\n".$wikiPage->text.'</textarea>');
$page->add('<textarea id="txtarea" cols=85 rows=30></textarea><br><br>');

$page->add('<input type="button" value="Salvar" id="save" onclick="javascript:Wiki_saveText();"/>');
$page->add('<input type="button" value="Visualizar" id="preview" onclick="javascript:Wiki_preview();" />');
$page->add('<input type="button" value="Cancelar" id="cancel" onclick="javascript:toggleEdit(\'cancel\');"/></div>');

$page->add(CMHTMLObj::getScript('wikiLoad("'.$wikiPage->title.'");'));

if($wikiPage->new == 1) {
	$page->add(CMHTMLObj::getScript('toggleEdit();'));
}

echo $page;

?>