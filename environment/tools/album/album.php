<?php
include("../../config.inc.php");

$_CMAPP['notrestricted'] = 1;
$_language = $_CMAPP['i18n']->getTranslationArray("album");

$page = new AMTAlbum;

$album = new AMAlbum;

$album->codeUser = $_SESSION['user']->codeUser;

$box = new AMBAlbum($album->getMyPhotos(),$_language['userImages']."".$_SESSION['user']->name);
$page->add("<br />");
$page->add($box);

echo $page;