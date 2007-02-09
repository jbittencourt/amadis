<?php
include_once("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("admin");

$pag = new AMTAdmin();

AMMain::addXOADHandler('AMAdminLogs', 'AMAdminLogs'); //register class handler
$pag->requires("amadminlogs.js", CMHTMLObj::MEDIA_JS); //require javascript for interation

//$box = new AMTwoColsLayout;


$box = new AMBAdminLogs;	

//$box2 = NULL;

//$box->add($box1,AMTwoColsLayout::LEFT);
//$box->add($box2,AMTwoColsLayout::RIGHT);

$pag->add($box);

echo $pag;

?>