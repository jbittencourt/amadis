<?

include("../../config.inc.php");

include_once("$rdpath/email/rdimapmail.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");
include_once("$pathtemplates/ambox.inc.php");
include_once("$pathtemplates/amcolorbox.inc.php");
include_once("$pathtemplates/amcorreio.inc.php");

$f_email = new RDImapMail();

$ui = new RDui("email");
$lang = $_SESSION[environment]->getLangUI($ui);


$pag = new AMCorreio();

if(!empty($_REQUEST[frm_Mailbox])) {
  $mailbox="frm_Mailbox=".$_REQUEST[frm_Mailbox];
  $f_email->setMailbox($_REQUEST[frm_Mailbox]);
};

//se o mailbox for sent ou trash, nao deixa responder
if(!($_REQUEST[frm_Mailbox]=="sent") || ($_REQUEST[frm_Mailbox]=="Trash")) {
  $itens[$lang[responder_email]] = "compose.php?acao=A_reply&frm_idMen=$_REQUEST[frm_idMen]&$mailox";
}
$itens[$lang[men_recebidas]] = "email.php";
//$itens[$lang[men_enviadas]] = $_SERVER[PHP_SELF]."?frm_Mailbox=sent";
$itens[$lang[enviar_men]] = "compose.php";

$pag->setSubMenu($itens);

$men = $f_email->getMensagem($_REQUEST[frm_idMen]);

$fg = "<font class=\"fontgray\">";
$s = "colspan=3";

$from = htmlentities($men->from_email);
$to = htmlentities($men->to);

$box = new AMColorBox("azul");

$box->add("<br><table style=\"left-margin:10px\" border=0 cellspacing=0 cellpadding=0 width=\"100%\">");
$box->add("<tr><td $s >$fg<b>De:</b> $from</td>");
$box->add("<tr><td $s >$fg<b>Para:</b> $to</td>");
$box->add("<tr><td $s >$fg<b>Assunto:</b> $men->subject</td>");
$box->add("<tr><td $s >$fg<b>Data:</b> $men->date</td>");
$box->add("<tr><td $s ><img src=\"$urlimagens/dot.gif\" height=10></td>");

$box->add("<tr>");

if(!is_array($men->body)) {
  switch($men->type) {
  case "text/plain":
    $body = nl2br(htmlentities($men->body));
    break;
  case "text/html":
    $body = $men->body;
    break;
	
  };

};

$box->add("<td ><img src=\"$urlimagens/dot.gif\" width=10 height=50></td>");
$box->add("<td width=100%>$body</td>");
$box->add("<td ><img src=\"$urlimagens/dot.gif\" width=10></td>");


$box->add("<tr><td $s ><img src=\"$urlimagens/dot.gif\" width=10 height=50></td>");

$box->add("<br><br><br></table>");
$pag->add($box);

$pag->imprime();

?>
