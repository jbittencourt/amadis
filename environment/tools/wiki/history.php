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
		new AMLog('AMWikiPage::Load', $e, AMLog::LOG_WIKI);
	}
}

$history = $wikiPage->getHistoy();

$page->requires('jsCrossmark.css', CMHTMLObj::MEDIA_CSS);

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

$page->add('<div class="section">');
$page->add('<h1>Histórico da '.implode(' ', explode('_', $wikiPage->title)).'</h1>');
if($history->__hasItems()) {
	$page->add('<table id="wiki-history">');
	$page->add('<tr class="row1"><th>Revision Number</th><th>Edition Hour</th><th>User</th><th>-</th></tr>');
	foreach($history as $item) {
		$color = $i%2 != 0 ? 'row1': 'row2';
		$i++; 
		$page->add('<tr class="'.$color.'"><td>'. $item->codeRevision .'</td>');
		$page->add('<td>'. date('d/m/Y - h:i:s', $item->time) . '</td>');
		$toolTip = new AMTUserInfo($item->users->items[0]);
		$page->add('<td>'. $toolTip .'</td>');
		$page->add('<td><a href="index.php?frm_namespace='.$wikiPage->namespace.'&frm_title='.$wikiPage->title.'&frm_revision='.$item->codeRevision.'">View</a></td></tr>');
	}
	$page->add('</table>');
}
$page->add('</div>');

echo $page;

?>