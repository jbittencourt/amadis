<?php
include("../../config.inc.php");

unset($_SESSION[amadis][chat][color]);

$_language = $_CMAPP[i18n]->getTranslationArray("chat");
$_CMAPP[smartform] = array();
$_CMAPP[smartform][language] = $_language;


//registra algumas variaveis de sessao uteis
// if (isset($_REQUEST[frm_codCurso])) {
//   unset($_SESSION[tipo_cod]);
//   $destino = "?frm_codCurso=";
//   $destino .= $_REQUEST[frm_codCurso]."&tipo=Curso";
//   $cod =$_REQUEST[frm_codCurso];
//   $tipo ="Curso";
//   $_SESSION[tipo_cod]->tipo = $tipo;
//   $_SESSION[tipo_cod]->cod = $_REQUEST[frm_codCurso];
//   $help = new AMChat();
//   $info = $help->getInfo($tipo,$_REQUEST[frm_codCurso]);
// }



if (isset($_REQUEST[frm_codProjeto])) {
  unset($_SESSION[tipo_cod]);
  $destino = "?frm_codProjeto=";
  $destino .= $_REQUEST[frm_codProjeto]."&tipo=Projeto";
  $cod =$_REQUEST[frm_codProjeto];
  $tipo ="Projeto";
  $_SESSION[tipo_cod]->tipo = $tipo;
  $_SESSION[tipo_cod]->cod = $_REQUEST[frm_codProjeto];
  $help = new AMChat();
  $info = $help->getInfo($tipo,$_REQUEST[frm_codProjeto]);
}


if (isset($_REQUEST[frm_codComunidade])) {
  unset($_SESSION[tipo_cod]);
  $destino = "?frm_codComunidade=";
  $destino .= $_REQUEST[frm_codComunidade]."&tipo=Comunidade";
  $cod =$_REQUEST[frm_codComunidade];
  $tipo ="Comunidade";
  $_SESSION[tipo_cod]->tipo = $tipo;
  $_SESSION[tipo_cod]->cod = $_REQUEST[frm_codComunidade];
  $help = new AMChat();
  $info = $help->getInfo($tipo,$_REQUEST[frm_codComunidade]);
}





//se nao houver codigo de curso ou de projeto.. temos um problema.
if((empty($_REQUEST[frm_codCurso])) AND (empty($_REQUEST[frm_codProjeto])) AND (empty($_REQUEST[frm_codComunidade]))){
  $pag = new AMTChat();
  if((empty($_REQUEST[frm_codCurso]))){
    $pag->addScript("window.alert('$_language[not_allowed]');location.href='../cursos/curso.php?frm_cod".$_SESSION[tipo_cod]->tipo."=".$_SESSION[tipo_cod]->cod."'");
  }
  if((empty($_REQUEST[frm_codProjeto]))){
    $pag->addScript("window.alert('$_language[not_allowed]');location.href='../projetos/projeto.php?frm_cod".$_SESSION[tipo_cod]->tipo."=".$_SESSION[tipo_cod]->cod."'");
  }
  if((empty($_REQUEST[frm_codComunidade]))){
    $pag->addScript("window.alert('$_language[not_allowed]');location.href='../communities/communities.php?frm_cod".$_SESSION[tipo_cod]->tipo."=".$_SESSION[tipo_cod]->cod."'");
  }
  echo $pag;

}
else{
  // deveria verificar a existencia de curso/projeto ?????
}




//deveria checar se aluno pertence ao curso/projeto/comunidade
// if (($_SESSION[environment]->eAluno($_SESSION[user]->codeUser,$_SESSION[tipo_cod]))==FALSE){
//   $pag=new AMTchat();
  
//   $pag->addScript("window.alert('$_language[not_allowed]');location.href='../webfolio/webfolio.php'");
  
//   echo $pag;
  
// }





switch($_REQUEST[tarefa]){

 default:

   $pag = new AMTChat();

   if($_REQUEST[erro] =="existe"){
     $pag->addScript("window.alert('$_language[chat_name_already_exists]');location.href='chat.php?frm_cod".$_SESSION[tipo_cod]->tipo."=".$_SESSION[tipo_cod]->cod."'");
     unset($_REQUEST[erro]);
   }

   if($_REQUEST[erro] =="data"){
     $pag->addScript("window.alert('$_language[cannot_schedule_in_past]');location.href='chat.php?frm_cod".$_SESSION[tipo_cod]->tipo."=".$_SESSION[tipo_cod]->cod."'");
     unset($_REQUEST[erro]);
   }


   
   //seta o timeout inicial da sala de chat



   



   //cria o formulario
   $requisitados = array("nomSala","desSala","datInicio","flaPermanente");
   $form1 = new CMWSmartForm(AMChat,"criarchat","chat.php".$destino,$requisitados);

   if(is_a($_SESSION[sala],AMChat)) {
     //$form->loadDataFromObject($_SESSION[sala]);
   }

   // $form1->components[flaPermanente]->setDesign("");
   $form1->setCancelOff();   
   $form1->setLabelClass("chatheader");

   
  //CMWData::setDate("datInicio","d/m/Y h:i",1);
   
   $tempo =time();
   $form1->setDate("datInicio","d/m/Y h:i:s",1);
   $form1->components[datInicio]->setValue($tempo);
   $form1->components[nomSala]->setSize(20);
   $form1->components[desSala]->setSize(20);
   
   
   $format = new CMHtmlFormat;
   $format->setTabela("table cellspacing=1 cellpadding=2 width=\"100%\"","/table");
  
   $form1->setHtmlFormat($format);
   
   $a = "<tr><td class=\"chatheader\">";
   $b = "</td><td>";
   $script =" \" if(agenda.style.display=='none'){ ";
   $script.="       agenda.style.display='' ";
   $script.="    }else{ ";
   $script.="       agenda.style.display='none' ";
   $script.="    };\"  ";
   
   $str = "$a &raquo;&nbsp;{LABEL_FRM_NOMSALA} $b {FORM_EL_FRM_NOMSALA} </td></tr>\n";
   $str.= "$a &raquo;&nbsp;{LABEL_FRM_DESSALA} $b {FORM_EL_FRM_DESSALA} </td></tr>\n";
   $str.= "$a &raquo;&nbsp; {LABEL_FRM_FLAPERMANENTE} $b {FORM_EL_FRM_FLAPERMANENTE} </td></tr>\n";
   $str.= "<tr><td><img src=\"$_CMAPP[imlang_url]/bt_chat_agendar.gif\" onClick=$script> $b {FORM_EL_SUBMIT_GROUP}  </td></tr>\n ";
   $str.= "<tr><td class=\"chatheader\" colspan=2> <div id=\"agenda\" name=\"agenda\" style=\"display: none;\">\n";
   $str.= " &raquo;&nbsp;{LABEL_FRM_DATINICIO}<br> {FORM_EL_FRM_DATINICIO}</div></td></tr>\n";
      
   $form1->setDesign(CMWFormEl::WFORMEL_DESIGN_STRING_DEFINED);
   $form1->setDesignString($str,1);
   //$form1->components[flaPermanente]->setDesign("");
   $form1->addComponent("tarefa",new CMWHidden("tarefa","salvar"));
   $salas = $_SESSION[environment]->listaChats($tipo,$cod);
   $time = time();
   $salasFuturas = $_SESSION[environment]->listaChatsFuturos($time,$tipo,$cod);
   
   //box definitivo  este box que cuida o template HTML geral do chat
   $chatBox = new AMBChat();
   




   $chatBox->addForm($form1);  
   $chatBox->addInfo($info,$cod);


   // verifica se usuario jah esta em alguma sala, caso afirmativo nao mostra salas abertas e exibe mensagem
   //$help = new AMChat();
   //$user_at_room = $help->is_user_in_chatroom($_SESSION[user]->codeUser, $salas);



   //if ($user_at_room=="sim"){
   //$chatBox->addMsg("sim","<a  class=\"linkchat\" href=chat.php?frm_cod".$_SESSION[tipo_cod]->tipo."=".$_SESSION[tipo_cod]->cod.">$_language[user_already_in_room]</a>");   
   //}
   //else{



   $chatBox->addSalasAbertas($salas);   
     //}
   $chatBox->addChatsFuturos($salasFuturas,$_language[scheduled_chats],$_language[no_scheduled_chats]);
   $pag->add($chatBox);
   
   break;



 case "salvar":
   
   $pag = new AMTChat();
   if(!is_a($_SESSION[sala],AMChat)){
     $_SESSION[sala] = new AMChat();
     $_SESSION[sala]->loadDataFromRequest();
     $_SESSION[sala]->tempo = time();
     $_SESSION[sala]->datInicio = time();
     $_SESSION[sala]->codeUser= $_SESSION[user]->codeUser;
   }
   //   note($_SESSION[sala]);die();
   //verifica se a sala jah existe com o mesmo nome
   //   echo date("h:i:s",$_SESSION[sala]->datInicio);


   if($_SESSION[sala]->verificaNomeExiste($_SESSION[sala]->nomSala)){
     unset($pag);
     $pag = new AMTChat();
     $pag->addScript("window.alert('$_language[chat_name_already_exists]');location.href='chat.php?frm_cod".$_SESSION[tipo_cod]->tipo."=".$_SESSION[tipo_cod]->cod."'");
     echo $pag;
     die();
     //header("Location: $_SERVER[PHP_SELF]?frm_cod".$_SESSION[tipo_cod]->tipo."=".$_SESSION[tipo_cod]->cod."&erro=existe"); 
     //die();
     //$pag = new AMTChat();
     //$pag->add("deu erro, nome de sala jah existe");

   }
 


   // DEVE SER REVISTO !!!!
   //------------------------------------------------------
   //aqui ele retira 1 hora do datInicio q veio do SmartForm com uma hora a mais
   //$_SESSION[sala]->datInicio = $_SESSION[sala]->datInicio-3600;
   $_SESSION[sala]->datInicio = $_SESSION[sala]->datInicio-1;
   $_SESSION[sala]->datFim = $_SESSION[sala]->datInicio+300                                  ;
   // ------------------------------------------------------


   //verifica se a sala estah sendo criada no passado, isso nao pode !!!
   if($_SESSION[sala]->datFim < time()){
     header("Location: $_SERVER[PHP_SELF]?frm_cod".$_SESSION[tipo_cod]->tipo."=".$_SESSION[tipo_cod]->cod."&erro=data");   
     //die();
     //$pag = new AMTChat();
     //$pag->add("deu erro, final do chat menor q o tempo");
   }

   if ($_SESSION[sala]->flaPermanente==1) 
     $perm =1;
   else $perm =0;
   
   $_SESSION[sala]->flaPermanente=$perm;
   $_SESSION[sala]->tempo = time();
   
   try {
     $_SESSION[sala]->save();
     //salva a sala de chat, e define se ela eh associada a um curso ou a um projeto

     if ($_REQUEST[tipo]=="Curso") {
       $curso = new AMCursoChats();
       $curso->codSala = $_SESSION[sala]->codSala;
       $curso->codCurso = (int)$_REQUEST[frm_codCurso];
       try {
	 $curso->save();
       }
       catch(CMDBQueryError $e){
	 $pag->addError("Nao foi possivel associar chat ao curso");
       }
       header("location: chat.php?frm_cod".$_SESSION[tipo_cod]->tipo."=".$_SESSION[tipo_cod]->cod);
       die();
     }

     if ($_REQUEST[tipo]=="Comunidade") {
       $comunidade = new AMComunidadeChats();
       $comunidade->codSala = $_SESSION[sala]->codSala;
       $comunidade->codComunidade = (int)$_REQUEST[frm_codComunidade];
       try {
	 $comunidade->save();
       }
       catch(CMDBQueryError $e){
	 $pag->addError("Nao foi possivel associar chat a comunidade");
       }
       $pag->addScript("location.href='chat.php?frm_cod".$_SESSION[tipo_cod]->tipo."=".$_SESSION[tipo_cod]->cod."'");

       
     }


     if ($_REQUEST[tipo]=="Projeto") {
       $projeto = new AMProjetoChats();
       $projeto->codSala = $_SESSION[sala]->codSala;
       $projeto->codProjeto = (int)$_REQUEST[frm_codProjeto];
       try {
	 $projeto->save();
       }
       catch(CMDBQueryError $e){
	 $pag->addError("Nao foi possivel associar chat ao projeto");
       }

       $pag->addScript("location.href='chat.php?frm_cod".$_SESSION[tipo_cod]->tipo."=".$_SESSION[tipo_cod]->cod."'");
     }


   }
   catch(CMDBQueryError $erro){
     $pag->addError("$_language[create_chat_error]  ".$erro.""); ;
   }
   

   break;

   echo $pag;

}  //fim do switch 


//unset($_SESSION[tipo_cod]);
unset($_SESSION[sala]);


echo $pag;


?>
