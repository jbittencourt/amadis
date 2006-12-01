<?
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("album");

$page = new AMTAlbum();

$album = new AMAlbum;

$album->codePhoto = $_REQUEST['frm_codePhoto'];

try{
  $album->load();
}catch(CMException $e){}

$box = new AMBAlbumZoom($album->getMyPhotos(),"Zoom", $album);

$page->add($box);

echo $page;
?>