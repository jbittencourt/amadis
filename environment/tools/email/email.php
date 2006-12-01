<?
include("../../config.inc.php");

include_once("$rdpath/email/rdimapmail.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");
include_once("$pathtemplates/ambox.inc.php");
include_once("$pathtemplates/amcorreio.inc.php");
include_once("$rdpath/email/rdemail.inc.php");


$f_email = new RDImapMail();

$ui = new RDui("email", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);


//limpa variaveis de secao
$_SESSION[AMADIS][EMAIL][COMPOSE]="";


$pag = new AMCorreio();


if(isset($_REQUEST[acao])) {

  switch($_REQUEST[acao]) {
  case "A_change_email":
    $pag->add("<br><br><div class=\"error\" align=center>$lang[email_desabilitado]</div>");

    $pag->add("<br><br><div class=\"fontgray\">");
    $temp = str_replace("{EMAIL}",$_SESSION[usuario]->strEMail,$lang[email_desabilitado_desc]);
    $pag->add($temp);
    $pag->add("</div>");

    $pag->add("<p><div align=center><a href=\"$_SERVER[PHP_SELF]?acao=A_change_email_make\" class=\"fontgray\">$lang[criar_novo_email]</a></div>");

    $pag->imprime();
    die();
    break;
    
  case "A_change_email_make":
    $_SESSION[usuario]->force_create_email = 1;
    $_SESSION[usuario]->strEMailAlt = $_SESSION[usuario]->strEMail;
    $_SESSION[usuario]->salva();

    break;
   
  case "A_deletaMen":
    if(!empty($_REQUEST[frm_menUid])) {
      foreach($_REQUEST[frm_menUid] as $m_uid) {
	$f_email->deleteMessage($m_uid);
      };
    }
    break;
  }
}



//valida o email do usuario para saber se ele tem um email pertencente a esse ambiente
if($config_ini[email][imap_email]) {
  $email = $_SESSION[usuario]->strEMail;
  $email = split("@",$email);
  $user = $email[0];
  $domain = $email[1];
  
  if($domain!=$config_ini[email][domain]) {
    Header("Location: $_SERVER[PHP_SELF]?acao=A_change_email");
    die("oops! Dominios nao conferem.");
  }

}


$itens[$lang[men_recebidas]] = $_SERVER[PHP_SELF];
$itens[$lang[men_enviadas]] = $_SERVER[PHP_SELF]."?frm_Mailbox=sent";
$itens[$lang[enviar_men]] = "compose.php";

$pag->setSubMenu($itens,"fontgray");


//pega as mensagens da ferramenta de correio.
if($_REQUEST[frm_Mailbox]=="sent") {
  $f_email->setMailbox($_REQUEST[frm_Mailbox]);
};

$mens = $f_email->listMessages();

$pag->add("<form name=mens action=\"$_SERVER[PHP_SELF]\" METHOD=POST>");
$pag->add("<input type=hidden name=acao value=\"A_deletaMen\">");

if ($_REQUEST[frm_Mailbox] == "sent") {
  $pag->add("<br><img src=\"$urlimlang/img_tit_mensagens_enviadas.gif\"><br>");
}
else {
  $pag->add("<br><img src=\"$urlimlang/img_tit_mensagens_recebidas.gif\"><br>");
}

$pag->add("<table border=0 cellspacing=0 cellpadding=0 width=\"100%\">");
$class = "tdtema";

if(!empty($mens)) {
  foreach($mens as $men) {
    if($class=="tdtema") { $class=""; } else { $class="tdtema"; };
    
    $pag->add("<tr>");
    
    if($men[unseen]) {
      $img = "$urlimagens/img_ico_carta.gif";
    } else {
      $img = "$urlimagens/img_ico_carta_open.gif";
    }

    if(!empty($_REQUEST[frm_Mailbox])) {
      $mailbox="frm_Mailbox=".$_REQUEST[frm_Mailbox];
    };    
    if(empty($men[subject])) $men[subject] = "(Sem assunto)"; 
    $pag->add("<td class=\"$class\" width=30><input type=checkbox name=\"frm_menUid[]\" value=\"$men[uid]\"></td>");
    $pag->add("<td class=\"$class\"><img src=\"$img\"></td>");
    $pag->add("<td class=\"$class\" width=50%><a href=\"vemen.php?frm_idMen=$men[uid]&$mailbox\" class=\"fontgray\">$men[subject]</a></td>");
    $pag->add("<td class=\"$class\" width=30%><font class=\"fontgray\">$men[from]</font></td>");
    //  $pag->add("<td>$men[date]</td>");
    $pag->add("<tr>");
  }
  
}
else {
  $pag->add("<td class=\"$class\" colspan=3><font class=\"fontgray\">$lang[nenhum_email]</font></td>");
}


$pag->add("<tr><td colspan=4 align=right><input type=submit value=$lang[apagar_email]></td>");
$pag->add("</table>");
$pag->add("</form>");
$pag->add("<br><br>");



$pag->imprime();

?>
