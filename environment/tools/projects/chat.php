<?php
$_CMAPP['notrestricted']=false;

include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("chat");

$pag = new AMTChat;

$pag->add("<br />");

$proj = new AMProject;

$proj->codeProject = $_REQUEST['frm_codeProject'];
try {
	$proj->load();
}catch (CMException $e) {
	die($e->getMessage());
}

$box = new AMBChat;

$box->setOpenRoomsImg("box_chat_salas_abertas.gif");
if(empty($proj->image)) $box->setThumb(new AMTProjectImage(AMProjectImage::DEFAULT_IMAGE, AMTProjectImage::METHOD_DEFAULT));
else $box->setThumb(new AMTProjectImage($proj->image));
$box->setTitle($proj->title);
$box->setToolType(AMChatRoom::ENUM_CHAT_TYPE_PROJECT, $proj->codeProject);

$openRooms = $proj->getOpenRooms();

if($openRooms->__hasItems()) {
	$box->addOpenRooms($openRooms);
}

$markedChats = $proj->getMarkedChats();
if($markedChats->__hasItems()) {
	$box->addMarkedChats($markedChats);
}

$pag->add($box);

echo $pag;