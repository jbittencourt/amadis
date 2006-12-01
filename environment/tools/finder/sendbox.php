<?

$_CMAPP['notrestricted'] = 1;
include("../../config.inc.php");


$_language = $_CMAPP['i18n']->getTranslationArray("finder");

if(isset($_REQUEST['action']) && $_REQUEST['action']=="A_send_message") {
  AMFinderMessages::sendMessage($_REQUEST['frm_codeUser'], $_REQUEST['frm_message']);
}

$pag = new CMHTMLPage;
$pag->setId("sendbox_window");
$pag->requires("finder.css", CMHTMLObj::MEDIA_CSS);
$pag->requires("mensagens.css", CMHTMLObj::MEDIA_CSS);
$pag->requires("lib.js", CMHTMLObj::MEDIA_JS);
//$pag->setOnload("window.parent.restoreConnection();");

$pag->add("<form name='messageForm' id='messageForm' action='$_SERVER[PHP_SELF]'>");
$pag->add("<input type='hidden' name='action' value='A_send_message'>");
$pag->add("<input type='hidden' name='frm_codeUser' value='$_REQUEST[frm_codeUser]'>");

$pag->add("<div id='main_enviarmsg'>");
$pag->add("<textarea id='frm_message' name='frm_message' cols='45' rows='3'></textarea>");
$pag->add("<input type='checkbox' id='autoScroll' checked>");

$pag->add("</div>");
$pag->add("<div class='envio'>");

$pag->add("<input class='btenvio' name='btenvio' value='$_language[send]' type='submit'>");
$pag->add("</div>");
$pag->add("</form>");

$pag->add(CMHTMLObj::getScript("AM_getElement('frm_message').focus();"));
echo $pag;

?>