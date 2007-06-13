<?php
include('../../config.inc.php');
$_language = $_CMAPP['i18n']->getTranslationArray("wiki");

$page = new AMHTMLPage;

if(!isset($_REQUEST['frm_namespace']) || empty($_REQUEST['frm_namespace'])) die('no namespace seted!');

$page->requires('wiki.js', CMHTMLObj::MEDIA_JS);

$wikiFile = new AMWikiFile;
$wikiFile->namespace = $_REQUEST['frm_namespace'];

if(isset($_REQUEST['action']) && $_REQUEST['action']=='save_image') {
	$wikiFile->save();
}

	
$files = $wikiFile->getFiles();

if(count($files) > 0) {
	foreach($files as $file) {
		
		$thumb = new AMLibraryThumb;
		$thumb->codeFile = (integer) $file['codeFile'];
		try {
			$thumb->load();
		} catch (CMException $e) { }

		$image = str_replace('image_', $thumb->codeFile.'_', $file['name']);
		$page->add('<img src="'.$thumb->thumb->getThumbUrl().'" onclick="Wiki_insertImageTag(\'../../files/'.$image.'\');"> '.$file['name'].'<br>');
		$page->add('<br clear="all">');
			
	}
}
	

$page->add('<form action="insert_image.php" method="post" enctype="multipart/form-data">');

$page->add('<label for="frm_image">Send Image</label><br>');
$page->add('<input type="file" name="frm_image" id="frm_image" />');
$page->add('<input type="hidden" name="frm_namespace" id="frm_namespace" value="'.$_REQUEST['frm_namespace'].'" />');
$page->add('<input type="hidden" name="action" id="action" value="save_image" /><br>');
$page->add('<input type="submit" name="submit" id="submit" value="'.$_language['send'].'" />');

$page->add('</form>'); 

echo $page;

?>