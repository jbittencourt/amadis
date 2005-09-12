<?
include_once("../../config.inc.php");

include_once("$rdpath/email/rdimapmail.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");
include_once("$pathtemplates/ambox.inc.php");
include_once("$pathtemplates/amcorreio.inc.php");
include_once("$pathtemplates/amcolorbox.inc.php");
include_once("$rdpath/email/rdemail.inc.php");
include_once("$rdpath/interface/rdjswindow.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");


$f_email = new RDImapMail();

$ui = new RDui("email", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AMCorreio();

$pag->add("<p><img src=\"$urlimlang/img_tit_mensagens_enviar.gif\"><br>");

$pag->requires("attach.js");
if(isset($_REQUEST[acao])) {
  switch($_REQUEST[acao]) {
  case "A_reply":
    
    if(!empty($_REQUEST[frm_Mailbox])) {
      $f_email->setMailbox($_REQUEST[frm_Mailbox]);
    };


    $men = $f_email->getMensagem($_REQUEST[frm_idMen]);
    $_REQUEST[nomUserDestino] = $men->from_email;
    $_REQUEST[frm_assunto] = "Re: ".$men->subject;

    $from = $men->from;
    if(empty($from)) $from = $men->from_email;
    if(!empty($men->body)) {
      $temp = explode("\n",$men->body);
      $_REQUEST[frm_mensagem] = "\n\n $lang[citando] ".$from."\n";
      foreach($temp as $linha) {
	$_REQUEST[frm_mensagem].= "> $linha";
      }
    }
    break;
  case "A_atachar":
    if(!empty($_FILES[frm_attach])) {
      
      $tmp_file = basename($_FILES[frm_attach][tmp_name].".att");
      copy($_FILES[frm_attach][tmp_name],$config_ini[Diretorios][pathtemp]."/$tmp_file");
      
      $_FILES[frm_attach][tmp_name] = $tmp_file; 
      
      $_SESSION[AMADIS_ESCOLA][EMAIL][COMPOSE][FILES][] = $_FILES[frm_attach]; 
    };
    
    break;
  case "A_compose_make": 
    
    if (!empty($_REQUEST[nomUserDestino])) {
      
      $message = $f_email->getNewMessage();
      $message->to = $_REQUEST[nomUserDestino];
      $message->subject = $_REQUEST[frm_assunto];
      $message->body = nl2br($_REQUEST[frm_mensagem]);


      if(!empty($_SESSION[AMADIS_ESCOLA][EMAIL][COMPOSE][FILES])) {
	foreach($_SESSION[AMADIS_ESCOLA][EMAIL][COMPOSE][FILES] as $file) {
	  $message->attachFile($file[data],$file[name],$file[type]);
	}
      }

      $message->send();
      $f_email->saveMessage($message);
      header("Location: $urlferramentas/email/email.php?acao=A_mail_enviado");
      die();

    }  
    break;

  }
}

$pag->add("<br>");



/**
 * Configura o SmartForm
 **/    
$form = new WSmartForm("RDEmailMen","envia",$_SERVER[PHP_SELF],array("codMensagem","codUser","nomPessoaEnviou","tempo"));
$form->setCancelUrl($_SERVER[PHP_SELF]."?flaEnviadas=".$_REQUEST[flaEnviadas]);
$form->setDesign(WFORMEL_DESIGN_STRING_DEFINED);


/**
 * Cria o campo para;
 **/    
$users_destino = new WTextArea("nomUserDestino",3,50,$_REQUEST[nomUserDestino]);
$users_destino->addLabel("Para:");
$users_destino->setValue($_REQUEST[nomUserDestino]);
$users_destino->prop[cols] = 45;
$form->addComponent("nomUserDestino",$users_destino);


/**
 * Configura o SmartForm
 **/    
$form->componentes[assunto]->addLabel("Assunto : ");
$form->componentes[assunto]->prop[size] = 54;
$form->componentes[assunto]->setValue($_REQUEST[frm_assunto]);
   


$form->componentes[mensagem]->addLabel("Mensagem : ");
$form->componentes[mensagem]->prop[cols] = 45;

if (!empty($_REQUEST[frm_mensagem])) {
  $form->componentes[mensagem]->addContent($_REQUEST[frm_mensagem]);
}



$form->setLabelClass("fontgray");

// $attach = new WFile("frm_attach");
// $attach->prop["onFocus"] = "onChangeAttach(this)";

// $form->addComponent("attach",$attach);
// $form->componentes[attach]->addLabel("Atachar arquivo : ");

$acao = new WHidden("acao","A_compose_make");
$form->addComponent("acao",$acao);



if(!empty($_SESSION[AMADIS_ESCOLA][EMAIL][COMPOSE][FILES])) {
  foreach($_SESSION[AMADIS_ESCOLA][EMAIL][COMPOSE][FILES] as $file) {
    $chk = new WCheckBox("attach_file[]",$file[name],$file[name]);
    $form->addComponent("file_$file[name]",$chk);
  }
}

$win = new RDJSWindow("$urlferramentas/agenda/addressbook.php?acao_pertinente=correio",$lang[livro_enderecos],600,400);
$link1 = $win->getScript();

$win = new RDJSWindow("$urlferramentas/userinfo/procura.php?acao_pertinente=correio",$lang[livro_enderecos],600,400);
$link2 = $win->getScript();

$img_find = "<a href=\"#\" onClick=\"$link2\"><img border=0 src=\"$urlimagens/find.png\"><a>";
$img_end = "<a href=\"#\" onClick=\"$link1\"><img border=0 src=\"$urlimagens/enderecos.png\"><a>";

$a = "<tr><td valign=top width=\"30%\" align=right class=\"fontgray\">";
$b = "</td><td valign=top colspan=2 >";
$c = "</td><td valign=top >";

$str = "$a {LABEL_FRM_NOMUSERDESTINO} $c {FORM_EL_FRM_NOMUSERDESTINO} </td><td> $img_find  $img_end </td>";
$str.= "$a {LABEL_FRM_ASSUNTO} $b {FORM_EL_FRM_ASSUNTO}</td>";
$str.= "$a {LABEL_FRM_MENSAGEM} $b {FORM_EL_FRM_MENSAGEM}</td>";
$str.="<TR><TD COLSPAN=3 ALIGN=CENTER>{FORM_EL_SUBMIT_BUTTONS}</TD>";

$form->setDesignString($str,1);

$box = new AMColorBox("azul");
$box->add($form);
$pag->add($box);

$pag->imprime();



?>
