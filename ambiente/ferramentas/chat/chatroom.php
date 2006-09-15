<?
include("../../config.inc.php");
$_language = $_CMAPP['i18n']->getTranslationArray("chatroom");

//se nao tiver codigo na sessao temos um problema
if(empty($_REQUEST['frm_codeRoom'])) {
  die($_language['access_denied']);
}

$room = new AMChatRoom;
$room->codeRoom = $_REQUEST['frm_codeRoom'];

try {
  $room->load();
}catch(CMDBNoRecord $e) {
  //sala nao existe
  echo "<b>$_language[invalid_room]</b>" ;
}
//$sala->timeOut = $sala->setTimeOut(300);
//timeout setado em +300



if($room->endDate < time()) {
  $room_closed = 1;
  //mostra somente o chat, sem o frame com a opcao de enviar
  $_REQUEST['acao']="A_chat";
  
}

if(!isset($_SESSION['amadis']['chat'])) $_SESSION['amadis']['chat'] = array();
//define randomicamente um cor de fundo pra o usuario
if(!isset($_SESSION['amadis']['chat']['color'])) {
  list($usec, $sec) = explode(' ', microtime());
  mt_srand((int) $sec + ((int) $usec * 10000));
  
  $color = array("persona_01",
		 "persona_02",
		 "persona_03",
		 "persona_04",
		 "persona_05",
		 "persona_06"
		 );
  
  $index =  mt_rand(0,count($color)-1);
  $_SESSION['amadis']['chat']['color'] = $color[$index];
}

AMChatMessages::sendMessage($room->codeRoom, 0, $_SESSION['user']->username." $_language[enter_room]");

$_SESSION['amadis']['chat'][$room->codeRoom] = array();
$_SESSION['amadis']['chat'][$room->codeRoom]['connection'] = AMChatConnection::enterRoom($room->codeRoom);
$_SESSION['amadis']['chat'][$room->codeRoom]['lastRequest'] = time();


$chatRoom = new AMBChatRoom($room);

$chatRoom->requires("chat.js", CMHTMLObj::MEDIA_JS);
$chatRoom->requires("scrollScript.js", CMHTMLObj::MEDIA_JS);

$chatRoom->setOnClose("Chat_closeChat();");
//window.setInterval(stringFunction, timeTable[obj[2]]+overtime);
$chatRoom->addPageBegin(CMHTMLObj::getScript("var gnm = window.setInterval(\"Chat_getNewMessages();\", 5000);"));
$chatRoom->addPageBegin(CMHTMLObj::getScript("var Chat_codeRoom = '$_REQUEST[frm_codeRoom]';"));
$chatRoom->addPageBegin(CMHTMLObj::getScript("var Chat_codeConnection = '".$_SESSION['amadis']['chat'][$room->codeRoom]['connection']."';"));
$chatRoom->addPageBegin(CMHTMLObj::getScript("var language_exit_room = '".$_SESSION['user']->username." $_language[exit_room]';"));

$chatRoom->addPageBegin(CMHTMLObj::getScript("var language_talk_to = '$_language[talk_to]';"));
$chatRoom->addPageBegin(CMHTMLObj::getScript("var language_all = '$_language[all]';"));

echo $chatRoom;


?>