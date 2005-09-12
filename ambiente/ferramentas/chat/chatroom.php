<?
include("../../config.inc.php");
$_language = $_CMAPP[i18n]->getTranslationArray("chatroom");
$_CMAPP[smartform] = array();
$_CMAPP[smartform][language] = $_language;



//se nao tiver codigo na sessao temos um problema
if(empty($_REQUEST[frm_codSala])) {
  //  echo $_REQUEST[frm_codSala];
  die($_language[access_denied]);
}


$cod_usuario = $_SESSION[user]->codeUser;

$sala = new AMChat();
$sala->codSala = $_REQUEST[frm_codSala];

try {
  $sala->load();
}
catch(CMDBNoRecord $e) {
  //sala na existe
  echo "<b>$_language[invalid_room]</b>" ;
}
$sala->timeOut = $sala->setTimeOut(300);
//timeout setado em +300



if($sala->datFim < time()) {
  $sala_fechada = 1;
  //mostra somente o chat, sem o frame com a opcao de enviar
  $_REQUEST[acao]="A_chat";
  
}



//define randomicamente um cor de fundo pra o usuario
if(empty($_SESSION[amadis][chat][color])) {
  list($usec, $sec) = explode(' ', microtime());
  mt_srand((int) $sec + ((int) $usec * 10000));
  
  $cor[] = "persona_01";
  $cor[] = "persona_02";
  $cor[] = "persona_03";
  $cor[] = "persona_04";
  $cor[] = "persona_05";
  $cor[] = "persona_06";

  $index =  mt_rand(0,count($cor));
  $_SESSION[amadis][chat][color] = $cor[$index];

}
$blame = $_SESSION[amadis][chat][color];

 //caso seja primeira entrada no chat do usuario
if(isset($_REQUEST[conexao])){
  switch($_REQUEST[conexao]){
  case "0":
       //verifica se usuario ainda esta conectado na sala , caso ele tenha saido, se estiver, nao deixa o usuario conectar
    if($sala->isLoggedChat($cod_usuario)){
      $pag = new CMHTMLPage();
      $pag->addScript("javascript:top.window.close()");
      echo $pag;   
      die();
    }
    if ($sala_fechada!=1){
      $_SESSION[conexao] = $sala->enterRoom($cod_usuario);
      $sala->sendMessage($cod_usuario,0," $_language[user_enter_room]",$blame,time()+3);
    }   
    break;
  default:
    break;
  }
}

if(isset($_REQUEST[acao])) {
  switch($_REQUEST[acao]) {
  case "A_chat":
    include_once($_CMAPP[path]."/templates/amtchattemplate.inc.php");
    $chat = new AMChatArea($sala);
    $chat->onlyShow = $sala_fechada;
    $tempo=0;
        
    if($sala_fechada)
      $tempo = $sala->datInicio-1;
        
    $status = $chat->mainLoop($tempo);

    if ($status=="sair"){
         
//       este trecho de criacao de arquivo eh apenas para fins de teste
//       $handle = fopen("/home/zukunft/chat.txt", "a");
//       $conteudo = "$status ---<><><>!!!\n";
//       fwrite($handle, $conteudo);
//       fclose($handle);
      unset($_SESSION[conexao]);
      $sala->sendMessage($cod_usuario,0," $_language[user_exit_room]",$blame);
      $saida = $sala->leaveRoom($cod_usuario);  
      die();
    }
    
    break;
    
  case "sair":
    unset($blame);
    //unset($_REQUEST[frm_codSala][$_REQUEST[frm_codSala]]);
    $pag = new CMHTMLPage();
    $pag->addScript("javascript:top.window.close()");
    echo $pag;   
    break;
   
  case "A_send_make":
    //cuida do envio das mensagens e gravacao no banco de dados
    switch($_REQUEST[destino]){
    case "0":   
      $sala->sendMessage($cod_usuario,$_REQUEST[destino],"$_REQUEST[desc_action] TODOS<br>".$_REQUEST[frm_mensagem],$blame);
      break;
    default:
      $usu = new AMUser();
      $usu->codeUser = $_REQUEST[destino];
      try{
	$usu->load();
      }
      catch(CMDBNoRecord $e){
	echo " $_language[user_not_logged] $e";
      }
      $sala->sendMessage($cod_usuario,$_REQUEST[destino],"$_REQUEST[desc_action] ".$usu->username."<br>".$_REQUEST[frm_mensagem],$blame,time());
      break;
    }

  case "A_send":
    //monta o frame de baixo pra envio de msgs e escolha de destinatario
    $script = "document.envia.frm_mensagem.focus()";
    $pag = new CMHTMLPage();
    $pag->setOnLoad($script);
    $pag->addStyle(".txtmsg {text-decoration: none; color: #336666; FONT-FAMILY: Arial;FONT-SIZE: 12px; line-height:20px; position: relative;}");
    $pag->addStyle("body { margin: 0px; } ");
    $pag->add("<form name=envia method=post action=\"chatroom.php?acao=A_send_make\">");
    $pag->add("<input type=hidden name=frm_codSala value=\"$sala->codSala\">");
    $pag->add("<table width=\"533\" border=0 cellspacing=0 cellpadding=0  height=64 >");
    $pag->add("<tbody><tr>");
    $pag->add("          <td style=\"padding-left: 15px;\" ");
    $pag->add("              class=\"txtmsg\"><input type=\"text\" size=\"8\" name=\"desc_action\" value=\"$_language[send_to]\"></td>");
    $pag->add("          <td rowspan=2 width=1 bgcolor=\"#ffffff\"></td>");
    $pag->add("          <td  class=\"txtmsg\">");
    $pag->add("              <b>$_language[msg]</b></td>");
    $pag->add("       </tr>");
    $pag->add("       <tr>");
    $pag->add("       <td style=\"padding-left: 15px;\">");
    $pag->add("           <select name=\"destino\">");
    $users = $sala->getConnectedUsers();
    $pag->add("<option value=0>$_language[all]</option>");
    foreach($users as $user){
      //if ($user->codUser!=$_SESSION[user]->codeUser){
	$buddy = new AMUser();
	$buddy->codeUser = $user->codUser;
	try{
	  $buddy->load();
	}
	catch(CMDBNoRecord $e){
	  echo "$_language[user_not_logged]";
	}
	$pag->add("<option value=".$buddy->codeUser.">".$buddy->username."</option>");
	//}
    }
    $pag->add("</select>");
    $pag->add("</td><td >");
    $pag->add("<input type=text name=\"frm_mensagem\"> <input type=\"submit\" name=\"submit\" value=\"$_language[sendmsg]\">");
    $pag->add("</td></tr></tbody></table>");
    $pag->add("<input type=hidden name=frm_scroll value=1>");
    $pag->add("</form>");

    echo $pag;
    die();

  case "topo":
    $pag = new CMHTMLPage();
    $pag->addStyle("body { background:#E1F7F9; margin: 0px; } ");
    $pag->addStyleFile($_CMAPP[url]."/media/css/tela_chat.css");
    $pag->addStyle(".tit_chat {text-decoration: none; color: #666666; FONT-FAMILY: Arial;FONT-SIZE: 13px; line-height:20px; position: relative;}");
    $pag->add("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">");
    $pag->add("<tbody>");
    $pag->add("<tr>");
    $pag->add("  <td rowspan=\"2\" valign=\"top\"><img src=\"$_CMAPP[images_url]/img_chat_01.gif\" border=\"0\" height=\"74\" width=\"142\"></td>");
    $pag->add("   <td background=\"$_CMAPP[images_url]/img_chat_02.gif\" valign=\"top\">");
    $pag->add("      <table border=\"0\" height=\"59\" width=\"388\">");
    $pag->add("      <tbody>");
    $pag->add("      <tr>");
    $info = $sala->getName($_SESSION[tipo_cod]->tipo,$_SESSION[tipo_cod]->cod);
    $pag->add("         <td class=\"tit_chat\" width=\"300\"><img src=\"$_CMAPP[images_url]/img_chat_balao.gif\"><b>Sala $sala->nomSala</b><br>");
    $pag->add("          no ". $_SESSION[tipo_cod]->tipo." <a href=\"#\" class=\"local\">".$info."</a>.</td>");
    $pag->add("         <td><a href=chatroom.php?acao=sair&frm_codSala=".$sala->codSala."> ");
    $pag->add("             <img src=\"$_CMAPP[images_url]/pt-br/img_chat_bt_sair.gif\" border=\"0\"></a></td>");
    $pag->add("     </tr>");
    $pag->add("	    </tbody></table>");
    $pag->add("	  </td>");
    $pag->add("</tr>");
    $pag->add("<tr>");
    $pag->add("   <td valign=\"top\"><img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"15\" width=\"15\"></td>");
    $pag->add("</tr>");
    $pag->add("</tbody></table>");
    
    echo $pag;
    die();

    break;
 
    default:
      //die("$_language[access_denied]");
  }
}



echo "<frameset rows=\"16,88,16\" frameborder=\"NO\" border=\"0\" framespacing=\"0\" cols=\"*\" > ";

echo "<frame name=\"header\" src=\"chatroom.php?acao=topo&frm_codSala=".$sala->codSala." \" >";
echo " <frame name=\"cima\" src=\"chatroom.php?acao=A_chat&frm_codSala=".$sala->codSala." \" >";
echo " <frame name=\"finder_envia\" src=\"chatroom.php?acao=A_send&frm_codSala=".$sala->codSala." \" >";




echo "</frameset>";







?>