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
	if(isset($_REQUEST['frm_revision']) && !empty($_REQUEST['frm_revision'])) {
		$wikiPage->getOldRevision($_REQUEST['frm_revision']);
	}
}

if(empty($wikiPage->text) && empty($_REQUEST['frm_title']) && $_REQUEST['frm_title'] != 'Main_Page') {
	$wikiPage->text = "\nMain Page\n============\n\nEsta é uma pagina para voce poder organizar todo o seu conteudo de pesquisa de seu projeto\n";
}else if(ereg('^image_', $wikiPage->title)) {
	$wikiPage->text = str_replace('image_', '', $wikiPage->title)."\n".str_repeat('=', strlen($wikiPage->title))."\n\n";
	$image = new AMWikiFile;
	$image->revision = $wikiPage->lastest;
	$image->load();
	$wikiPage->text .= '<image ../../files/'.str_replace('image_', $image->file.'_', $wikiPage->title).'">';
}else if(empty($wikiPage->text)) {
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
	<li><a href="index.php?frm_namespace='.$wikiPage->namespace.'&frm_title='.$wikiPage->title.'">'.$_language['article'].'</a></li>
	<li>'.$_language['discusion'].'</li>
	<li><a href="javascript:void(0);" onclick="javascript:toggleEdit();">'.$_language['edit'].'</a></li>
	<li><a href="history.php?frm_namespace='.$wikiPage->namespace.'&frm_title='.$wikiPage->title.'">'.$_language['history'].'</a></li>
	<li>'.$_language['move'].'</li>
	<li>'.$_language['watch'].'</li>
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
$page->add('<div id="edit_toolbar">'.CMHTMLObj::getScript('Wiki_loadToolBar();').'</div>');

$page->add('<textarea style="display: none;" id="'.$wikiPage->title.'">'."\n".$wikiPage->text.'</textarea>');
$page->add('<textarea id="txtarea" cols=85 rows=30></textarea><br /><br />');

$page->add('<input type="button" value="Salvar" id="save" onclick="javascript:Wiki_saveText();"/>');
$page->add('<input type="button" value="Visualizar" id="preview" onclick="javascript:Wiki_preview();" />');
$page->add('<input type="button" value="Cancelar" id="cancel" onclick="javascript:toggleEdit(\'cancel\');"/></div>');

$page->add(CMHTMLObj::getScript('wikiLoad("'.$wikiPage->title.'");'));

if($wikiPage->new == 1) {
	$page->add(CMHTMLObj::getScript('toggleEdit();'));
}
$page->add('<div id="Wiki_insertImageWindow" style="left:0px; top:0px;">');
$page->add('<a href="javascript:Wiki_close(\'Wiki_insertImageWindow\');">');
$page->add('<img src="'.$_CMAPP['images_url'].'/up_janela_fechar.gif" class="close-button"></a>');
$page->add('<iframe width="100%" height="99%" scrolling="auto" src="insert_image.php?frm_namespace='.$wikiPage->namespace.'"></iframe>');
$page->add('</div>');

$page->add(CMHTMLObj::getScript('AM_getElement(\'jsCrossMark_editArea\').onmousemove = getMouseXY;'));
echo $page;

?>