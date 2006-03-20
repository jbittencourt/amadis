<?
$_CMAPP['notrestricted']=false;
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("chatroom");

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'A_send') {
  
  AMChatMessages::sendMessage($_REQUEST['frm_codeRoom'], 
			      ($_REQUEST['frm_codeRecipient']==$_language['all'] ? 0 : $_REQUEST['frm_codeRecipient']),
			      $_REQUEST['frm_message']
			      );
}


$pag = new CMHTMLPage;

$pag->requires("chat.css", CMHTMLObj::MEDIA_CSS);
$pag->requires("lib.js", CMHTMLObj::MEDIA_JS);

$pag->add("<form action='$_SERVER[PHP_SELF]' method='POST' onSubmit=\"if(AM_getElement('message').value.length==0) return false; else return true;\">");
$pag->add("<div class='Isendbox'>");

$pag->add("<div class='message_left'>$_language[talk_to]:<br>");
$pag->add("<select name='frm_codeRecipient'>");

$pag->add("<option value='$_language[all]'>$_language[all]</option>");

$users = AMChatConnection::getConnectedUsers($_REQUEST['frm_codeRoom']);

if($users->__hasItems()) {
  foreach($users as $item) {
    if($item->user[0]->username != $_SESSION['user']->username)
      $pag->add("<option value='".$item->codeUser."'>".$item->user[0]->username."</option>");
  }
}
$pag->add("</select>");

$pag->add("</div>");

$pag->add("<div class='message_right'>");
$pag->add("$_language[message]<br><input type='text' size='30' id='message' name='frm_message'>&nbsp;");
$pag->add("<input type='submit' value='$_language[send]'>");
$pag->add("</div>");

$pag->add("</div>");
$pag->add("<input typt='hidden' name='action' value='A_send'>");
$pag->add("<input typt='hidden' name='frm_codeRoom' value='$_REQUEST[frm_codeRoom]'>");
$pag->add("");
$pag->add("</form>");

$pag->addPageEnd(CMHTMLObj::getScript("AM_getElement('message').focus();"));
sleep(2);
echo $pag;

?>