<?

include_once("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("finder");

include("cminterface/cmhtmlpage.inc.php");
$pag = new CMHtmlPage;

$pag->addPageBegin(XOAD_Utilities::header("$_CMAPP[media_url]/lib/xoad"));
$pag->addPageBegin(CMHTMLObj::getScript("var AMFinder = ".XOAD_Client::register(new AMFinder)));
$pag->addPageBegin(CMHTMLObj::getScript("AMFinder_lang['conversation_timeout'] = '$_language[conversation_timeout]';"));

$pag->requires("mensagens.css", CMHTMLObj::MEDIA_CSS);
$pag->requires("lib.js", CMHTMLObj::MEDIA_JS);
$pag->requires("finder.js", CMHTMLObj::MEDIA_JS);
$pag->requires("scrollScriptFrame.js", CMHTMLObj::MEDIA_JS);

$pag->setTitle("AMADIS Instant Messages - AMFinder");

//init Finder object
$pag->setOnClose("window.opener.Finder_window=null;");//Finder_closeAllFinder();");
//$pag->addPageBegin(CMHTMLObj::getScript("AMFinder_Timeout = window.setInterval(\"Finder_getNewMessages();\", 5000);"));

$pag->setId("chatBody");
//$pag->setOnClose("Finder_closeFinder('".$_SESSION[user]->codeUser."_".$user->codeUser."');");
//$pag->setOnLoad("Finder_initChat();");

$pag->add("<div id='ChatTabs' style='margin-left:6px;'></div>");

$pag->add("<div id='ChatContainer'>");
//chatcorpo
//$pag->add(new AMBFinderConversation($user, $_SESSION[user]->codeUser."_".$user->codeUser));

//chatContainer
$pag->add("</div>");

$pag->addPageEnd(CMHTMLObj::getScript("var ChatTabs = document.getElementById('ChatTabs');"));
$pag->addPageEnd(CMHTMLObj::getScript("var ChatContainer = document.getElementById('ChatContainer');"));
$pag->addPageEnd(CMHTMLObj::getScript("AMFinder.addChat('{$_SESSION[user]->codeUser}_$_REQUEST[frm_codeUser]',AMFinderCallBack.onAddChat);"));
$pag->addPageEnd(CMHTMLObj::getScript("window.setInterval('window.opener.Finder_window = window',3);"));
echo $pag;

?>
