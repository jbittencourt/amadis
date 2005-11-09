<?

include_once("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("finder");

if(empty($_REQUEST['frm_codeUser'])) {
  die("Nao sei com quem conversar");
}else {
  $user = new AMUser;
  $user->codeUser = $_REQUEST['frm_codeUser'];
  try {
    $user->load();
  }catch (CMDBNoRecord $e) {
    die($e->getMessage());
  }
}

AMFinder::initFinder($_SESSION['user']->codeUser, $user->codeUser);

// AMadis
// Instant
// Messenger
include("cminterface/cmhtmlpage.inc.php");
$pag = new CMHtmlPage;

$pag->requires("mensagens.css", CMHTMLObj::MEDIA_CSS);
$pag->requires("dcom.js", CMHTMLObj::MEDIA_JS);
$pag->requires("lib.js", CMHTMLObj::MEDIA_JS);
$pag->requires("finder.js", CMHTMLObj::MEDIA_JS);
$pag->requires("communicator.php?client", CMHTMLOBJ::MEDIA_JS);

$pag->setTitle("$user->username - $user->name");

//init Finder object
$pag->addPageBegin(CMHTMLObj::getScript("var AMFinder = new amfinder(AMFinderCallBack);"));

//setTimeOut to check new messages
$script = "AMFinder_timeOut = self.setInterval('AMFinder.getnewmessages(".$_SESSION['user']->codeUser.", $user->codeUser)',".AMFinder::getSleepTime().")";

$pag->addPageBegin(CMHTMLObj::getScript($script));
$pag->setId("chatBody");
//$pag->setOnClose("window.opener.Finder_closeFinder($user->codeUser);");
$pag->setOnLoad("Finder_initChat();");
$pag->add("<div id=\"chatcorpo\"> ");
$pag->add("  <div id=\"area_mensagens\" class=\"sobre\"> ");
$pag->add("    <div id=\"areatextochat\">");

//box list messages
$pag->add("      <iframe  id='chat' name='chat' src='$_CMAPP[media_url]/dcom.htm'></iframe>");

$pag->add("    </div>");
$pag->add("    <div id=\"seta1\" class=\"posicaoseta\"><img src=\"$_CMAPP[images_url]/box_msg_areachat_01.png\" width=\"14\" height=\"10\" border=\"0\"></div>");
$pag->add("    <div id=\"seta2\" class=\"posicaoseta\"><img src=\"$_CMAPP[images_url]/box_msg_areachat_02.png\" width=\"14\" height=\"10\" border=\"0\"></div>");
$pag->add("  </div>");
$pag->add("  <div id=\"area_informacao\" class=\"sobre\"><span id=\"traco\"></span> ");
$pag->add("    <div id=\"areainfocorpo\"><img src=\"$_CMAPP[images_url]/box_msg_pecas.png\" width=\"79\" height=\"121\" border=\"0\"></div>");
$pag->add("<div id=\"infoelement\">");

//add a user recipient thumbnail
$userThumb = new AMUserThumb;
$userThumb->codeArquivo = $user->foto;
try {
  $userThumb->load();
  $userThumbURL = $userThumb->thumb->getThumbURL();
  $pag->add("<img src=\"$userThumbURL\" class=\"element\"><br>");
}catch (CMDBException $e) {
  $pag->add("$_language[error_loading_image]");
}

$pag->add("$user->username<br>");

$pag->add("<img class=\"setas\" src=\"$_CMAPP[images_url]/box_msg_setas.png\"><br>");

//add a user sender thumbnail
$userThumb = new AMUserThumb;
$userThumb->codeArquivo = $_SESSION['user']->foto;

try {
  $userThumb->load();
  $userThumbURL = $userThumb->thumb->getThumbURL();
  $pag->add("<img src=\"$userThumbURL\" class=\"element\"><br>");
}catch (CMDBException $e) {
  $pag->add("$_language[error_loading_image]");
}

$pag->add($_SESSION['user']->username."<br>");

$pag->add("    </div>");
$pag->add("<div id=\"footerlement\"><img src=\"$_CMAPP[images_url]/box_msg_pecas2.png\"></div>");
$pag->add("  </div>");
$pag->add("  <div id=\"area_enviarmsg\">");

$pag->add("    <form name=\"messageForm\" id=\"messageForm\">");
$pag->add("    <input type=\"hidden\" name=\"action\" value=\"A_send_message\">");
$pag->add("    <input type=\"hidden\" name=\"frm_codeRecipient\" value=\"$user->codeUser\">");

$pag->add("    <div id=\"main_enviarmsg\">");
$pag->add("      <textarea id=\"frm_message\" name=\"frm_message\" cols=\"45\" rows=\"3\"></textarea>");
$pag->add("      <input type=\"checkbox\" id=\"autoScroll\" checked>");

$pag->add("    </div>");
$pag->add("  <div class=\"envio\">");

$onClick = "onClick=\"Finder_sendMessage(AM_getElement('messageForm'))\"";

$pag->add("    <input class=\"btenvio\" name=\"btenvio\" value=\"$_language[send]\" type=\"button\" $onClick>");
$pag->add("  </div>");
$pag->add("    </form>");
$pag->add("    <div id=\"seta3\" class=\"posicaoseta\"><img src=\"$_CMAPP[images_url]/box_msg_areaenvio_03.png\"></div>");
$pag->add("    <div id=\"seta4\" class=\"posicaoseta\"><img src=\"$_CMAPP[images_url]/box_msg_areaenvio_04.png\"></div>");
$pag->add("  </div>");

$pag->add("</div>");


echo $pag;

?>
