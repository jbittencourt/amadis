<?
$_CMAPP['notrestricted']=false;

include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("chat");

$pag = new AMTChat;

$pag->setOnClose("ajaxSync.send();");

AMMain::addCommunicatorHandler('AMChat');

$pag->addPageBegin(CMHTMLObj::getScript("var AMChat = new amchat(AMChatCallBack);"));

$pag->add("<br>");

$community = new AMCommunities;
$community->code = $_REQUEST['frm_codeCommunity'];
try {
  $community->load();
}catch (CMException $e) {
  die($e->getMessage());
}

$box = new AMBChat;

$box->setOpenRoomsImg("box_chat_salas_abertas.gif");
$box->setThumb(new AMTCommunityImage($community->image));
$box->setTitle($community->name);
$box->setToolType(AMChatRoom::ENUM_CHAT_TYPE_COMMUNITY, $community->code);

$openRooms = $community->getOpenRooms();

if($openRooms->__hasItems()) {
  $box->addOpenRooms($openRooms);
}

$markedChats = $community->getMarkedChats();
if($markedChats->__hasItems()) {
  $box->addMarkedChats($markedChats);
}

$pag->add($box);

echo $pag;
?>