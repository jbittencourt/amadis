<?php
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("album");

$page = new AMTAlbum();

$album = new AMAlbum;
$user = new AMUser;

$album->codeUser = $_REQUEST['frm_codeUser'];
$user->codeUser = $_REQUEST['frm_codeUser'];
try{
  $user->load();
}catch(CMException $e){ }

$page->add("<a href='$_CMAPP[services_url]/webfolio/userinfo_details.php?frm_codeUser=$user->codeUser'>&laquo;".$_language['back']."</a>");
$box = new AMBViewAlbum($album->getMyPhotos(),$_language['userImages']."".$user->name);
$page->add("<br />");
$page->add($box);

echo $page;