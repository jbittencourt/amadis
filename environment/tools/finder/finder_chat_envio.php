<?
$r_bibliotecas[] = "\$rdpath/finder/rdfinder.inc.php";
include_once("../../config.inc.php");

$ui = new RDUi("finder_envio",$_REQUEST[acao]);
$lang = $_SESSION[ambiente]->getLangUI($ui);


if(empty($_REQUEST[frm_user])) {
  die($lang[nobody]);
};


if(isset($_REQUEST[acao])) {
  switch($_REQUEST[acao]) {
  case "A_envia":
    $_SESSION[finder]->enviaMensagem($_REQUEST[frm_user],$_REQUEST[frm_texto]);
    break;
  };
};


$pag = new RDPagina();
$pag->setMargin(0,0,0,0);
$pag->add("<table border=0 background=\"$urlimagens/bg_barra_chat.gif\" cellpadding=0 cellspacing=0 style=\"table-layout:fixed\" width=\"100%\" height=\"100%\">");
$pag->add("<tr><td>");

$pag->add("<form name=envia method=post action=\"$_SERVER[PHP_SELF]\">");
$pag->add("\t<textarea name=frm_texto rows=2 cols=25 wrap></textarea>");
$pag->add("<input type=submit value=\"Enviar\">");
$pag->add("\t<input type=hidden name=acao value=\"A_envia\">");
$pag->add("\t<input type=hidden name=frm_user value=$_REQUEST[frm_user]>");
$pag->add("\t<br /><input type=checkbox name=frm_scroll checked>Scroll");
$pag->add("</form>");

$pag->add("</table>");

$pag->imprime();

?>