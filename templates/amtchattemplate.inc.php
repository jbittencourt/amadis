<?php

/**
 * The AMBoxDiario is a box that list blog entries.
 * @ignore
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMDiary
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMChatArea extends CMHtmlObj {

  var $campoDest,$campoSender, $campoTempo, $tempoSleep, $CHAT_cod_user;
 
  function AMChatArea($sala) {
    $finder = $config_ini['Chat'];

    $this->campoDest = "codDestino";
    $this->campoSender = "codRemetente";
    $this->campoTempo = "tempo";

    $this->sala = &$sala;
    $this->CHAT_cod_user = $_SESSION['user']->codeUser;
    $this->setSleepTime(4);

    $this->sala = &$sala;

  }




  
  // function AMChatTemplate() {
//     $this->tempoSleep = 2;  //seta epera para 2 segundos 
  //}


  function setSleepTime($tempo) {
    $this->tempoSleep = 2;
  }


  function listaMensagensDesde($tempo=0,$codUser=0) {
    global $_CMAPP;
    include_once("$_CMAPP[path]/lib/amchatmensagem.inc.php");
    $sql = "codSalaChat=".$this->sala->codSala." AND tempo > ".$tempo;//." AND codRemetente=".$codUser;
    $this->query = new CMQuery('AMChatMensagem');
    $this->query->setFilter($sql);
    $mensagens = $this->query->execute();
    return $mensagens;
  }


  function getNewMessages($time) {
    $mens = $this->listaMensagensDesde($time);
    
    //note($mens);
    $mensagens = array();
    if(!empty($mens->items)) {
      foreach($mens as $item) {
	$mensagens[] = array("campoDest"=>$item->codDestinatario,
			     "campoSender"=>$item->codRemetente,
			     "campoTempo"=>$item->tempo,
			     "desMensagem"=>$item->desMensagem,
			     "desTag"=>$item->desTag,
			     "codSalaChat"=>$item->codSalaChat,
			     "codMensagem"=>$item->codMensagem
			     );
      }
      
    }
    
    return $mensagens;
  
  }



  function drawMessage($men) {
  
    $user = new AMUser();
    $user->codeUser = $men['campoSender'];
    try{
      $user->load();
    }
    catch(CMDBNoRecord $e){
      echo "Usuario nao existe erro $e";
    }
    $hora = date("H:i:s",$men['campoTempo']);

    echo "<table width=\"510\" border=\"0\" noshade cellspacing=\"0\" cellpadding=\"0\">";
    echo "<tr>";
    echo "<td class=\"$men[desTag]\" valign=\"top\" class=\"perfil\" >";
    echo "<br> <b>$user->username</b>";
    $acao = explode("<br>", $men['desMensagem']);
    
    echo "&nbsp; $acao[0]</td>";
    echo "<td valign=\"top\" width=\"70%\"class=\"$men[desTag]\">";
    $texto = new AMSmileRender($acao[1]);
    echo $texto->__toString();
    echo "</td></tr>";
    echo "</table>";

    return (strlen($men['desMensagem'])/40);
  }
 

  function mainLoop($tempoLastMessage="") {
    ignore_user_abort(TRUE);
    set_time_limit(0);
    session_write_close();    
    if(empty($tempoLastMessage)) 
      $tempoLastMessage = time(); 

    $this->scrollScript();
    //imprime os cabecalhos da pagina
    echo "<html>";
    echo "<head>";
    echo "<style type=\"text/css\">";
    echo " a.perfil:link {text-decoration: none; color: #666666; FONT-FAMILY: Arial;FONT-SIZE: 12px; line-height : 20px; position: relative; font-weight: bold;}    a.perfil:visited {text-decoration: none; color: #666666; FONT-FAMILY: Arial;FONT-SIZE: 12px; line-height : 20px; position: relative; font-weight: bold;}    a.perfil:active {text-decoration: none;color: #666666;FONT-FAMILY: Arial;FONT-SIZE: 12px;line-height : 20px;position: relative;font-weight: bold;}    a.perfil:hover {text-decoration: underline; color: #3666666; FONT-FAMILY: Arial;FONT-SIZE: 12px; line-height : 20px; position: relative; font-weight: bold;}    .tit_chat {text-decoration: none; color: #666666; FONT-FAMILY: Arial;FONT-SIZE: 13px; line-height:20px; position: relative;}    .local {text-decoration: underline; color: #666666; FONT-FAMILY: Arial;FONT-SIZE: 13px; line-height:20px; position: relative;}    .txtmsg {text-decoration: none; color: #336666; FONT-FAMILY: Arial;FONT-SIZE: 12px; line-height:20px; position: relative;}    .persona_01 {border-top-width: 1px; border-bottom-width: 1px; border-left-width: 0px; border-right-width: 1px;border-style: solid; border-color: #E1F7F9;background-color: #FFFFDF; padding: 7px;    font-family: verdana, Helvetica, sans-serif;font-size: 11px;color: #666666;    }    .persona_02 {border-top-width: 1px; border-bottom-width: 1px; border-left-width: 0px; border-right-width: 1px;border-style: solid; border-color: #E1F7F9;background-color: #FFE2B1; padding: 7px;    font-family: verdana, Helvetica, sans-serif;font-size: 11px;color: #666666;    }    .persona_03 {border-top-width: 1px; border-bottom-width: 1px; border-left-width: 0px; border-right-width: 1px;border-style: solid; border-color: #E1F7F9;background-color: #FFCB27; padding: 7px;    font-family: verdana, Helvetica, sans-serif;font-size: 11px;color: #666666;    }    .persona_04 {border-top-width: 1px; border-bottom-width: 1px; border-left-width: 0px; border-right-width: 1px;border-style: solid; border-color: #E1F7F9;background-color: #FFEE5E; padding: 7px;    font-family: verdana, Helvetica, sans-serif;font-size: 11px;color: #666666;    }        .persona_05 {border-top-width: 1px; border-bottom-width: 1px; border-left-width: 0px; border-right-width: 1px;border-style: solid; border-color: #E1F7F9;background-color: #FFEE5E; padding: 7px;    font-family: verdana, Helvetica, sans-serif;font-size: 11px;color: #666666;    }    .persona_06 {border-top-width: 1px; border-bottom-width: 1px; border-left-width: 0px; border-right-width: 1px;border-style: solid; border-color: #E1F7F9;background-color: #FAF8FA; padding: 7px;    font-family: verdana, Helvetica, sans-serif;font-size: 11px;color: #666666;   }";


    echo "</style>";
    echo "</head>";
    echo "<body bgcolor=\"#E1F7F9\"> ";    
 
    while((!connection_aborted()) && (!$onlyShow))  {  
      $onlyShow = $this->onlyShow;
      $mensagens = $this->getNewMessages($tempoLastMessage);      //pega as novas mensagens
      echo " \n";
      //flush();
      if (!empty($mensagens)) {
	foreach($mensagens as $mensagem) {  
	  $numLinhas = $this->drawMessage($mensagem);
	  $tempoLastMessage = $mensagem['campoTempo'];
	  //este o script que chama o scroll da tela
          $scroll= "\n<SCRIPT language=\"JavaScript\" type=\"text/javascript\">";
          $scroll.= " scrollTela(".$numLinhas.") ";
          $scroll.= "</SCRIPT>\n";
          echo $scroll;
          flush();
        }
      } 
      sleep($this->tempoSleep);   
      flush();
    }
    flush();
    if (connection_aborted()){
      $status = "sair";
      return $status;
    }
    
  }  
    
    

  function scrollScript() {
    $script= "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">";
    $script.= "function doScroll(numLinhas) {";
    $script.= "window.scrollBy(0,1);";
    $script.= "if (browser==\"opera\") {";
    $script.= "     window.scrollBy(0,40);";
    $script.= "     for(i=1;i<=numLinhas;i++) {";
    $script.= "     window.scrollBy(0,10); }";   
    $script.= "}";    
    $script.= "else if (isNaN(window.pageYOffset))";
    $script.= " { window.scrollTo(0,document.body.scrollHeight);}";
    $script.= "else ";
    $script.= " { if (document.layers) {";
    $script.= "     window.scrollBy(0,47);";
    $script.= "     for(i=1;i<=numLinhas;i++) {";
    $script.= "     window.scrollBy(0,14); }";   
    $script.= "   }";
    $script.= "   else {";
    $script.= "     window.scrollTo(0,document.body.offsetHeight);}";   //By0,42
    $script.= "}";
    $script.= "}";

    $script.= "function scrollTela(numLinhas) {";
    $script.= " if (browser==\"opera\") {";
    $script.= " if (parent.finder_envia.document.envia.frm_scroll.checked==true)";    
    $script.= "   { doScroll(numLinhas); }";
    $script.= "}";
    $script.= "if (browser!=\"opera\") {";
    $script.= "if (!isNaN(window.pageYOffset)) {";
    $script.= "  if (window.pageYOffset<posicaoY) {";
    $script.= "    parent.finder_envia.document.envia.frm_scroll.checked = false; }";    
    $script.= "  posicaoY = window.pageYOffset; } ";                   
    $script.= "else if (!isNaN(document.body.scrollTop)) {";
    $script.= "  if (document.body.scrollTop<posicaoY) {";
    $script.= "    parent.finder_envia.document.envia.frm_scroll.checked = false; }";    
                     
    $script.= "}";
    $script.= "}";
    $script.= "if (browser!=\"opera\") {";
    $script.= "if (parent.finder_envia.document.envia.frm_scroll.checked==true) {";
    $script.= "   doScroll(numLinhas); }";    
    $script.= "else if (!isNaN(window.pageYOffset)) {" ;
    $script.= "   if ((document.body.offsetHeight-window.pageYOffset)<(window.innerHeight+70))";    //70
    $script.= "     {  doScroll(numLinhas);} ";    
    $script.= " }";  
    $script.= "else if (!isNaN(document.body.scrollTop)) {";
    $script.= "   if ((document.body.scrollTop+document.body.clientHeight)>=(document.body.scrollHeight-90))"; //90
    $script.= "     {  doScroll(numLinhas);}";
    $script.= "}";      
    $script.= " if (isNaN(window.pageYOffset)) {";
    $script.= "    posicaoY = document.body.scrollTop; }";                        
    $script.= " else {";
    $script.= "    posicaoY = window.pageYOffset; }";    
    $script.= "}";
    $script.= "}";
    $script.= "posicaoY=0;";
    $script.= "detect = navigator.userAgent.toLowerCase();";
    $script.= "if (detect.indexOf('opera')!=-1)";    
    $script.= "  { browser=\"opera\"; }";    
    $script.= "else ";
    $script.= "  { browser = \"outro\"; }";
    $script.= "</SCRIPT>";  
    echo $script;
    flush();
  }






} //fim amchatarea





?>