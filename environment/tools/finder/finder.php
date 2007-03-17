<?

include_once("../../config.inc.php");

echo AMShared::share('unshared_26');

die();
//AMEnvSession::getFinderRequest();

AMFinder::initFinder("34_17");

$f = new AMFinder;

note($f->getNewMessages("34_17"));

$e = new AMEnvSession;
note($e->getFinderRequest());
die();


$pag = new AMMain;
//$pag = new CMHTMLPage;
$_language = $_CMAPP['i18n']->getTranslationArray("finder");
$_SESSION['communicator'][1] = 'AMFinder';

$pag->add(CMHTMLObj::getScript("var AMFinder = new amfinder(AMFinderCallBack);"));

$pag->add(CMHTMLObj::getScript("function teste() { AMFinder.getmodes(); }"));

$pag->add("<input type='button' name='check' value='Test' onclick='teste(); return false;'>");

AMFinder::initFinder(34,39);
$f = new AMFinder();
$f->sendMessage(39,"teste forcado");
$a = $f->getNewMessages(34,39);
note($a);
note($f);
notelastquery();
//echo $pag;

die();
if(empty($_SESSION['finder'])) {
  $_SESSION['finder'] = new AMFinder();
};

switch($_REQUEST[action]) {
 case "A_change_mode":
   if(isset($_REQUEST[frm_mode])) {
     $_SESSION[finder]->changeMode($_REQUEST[frm_mode]);
   }
   die(note($_REQUEST));
   break;
 case "A_remove_request":
   if(isset($_REQUEST[frm_codeRequest])) {
     $message = new AMFinderMessages;
     $message->code = $_REQUEST[frm_codeRequest];
     try {
       $message->load();
       $message->status = AMFinderMessages::ENUM_STATUS_READ;
       $message->save();
     }catch (CMDBNoRecord $e) {
       die($e->getMessage());
     }
   }
   die(note($_REQUEST));
   break;
 case "A_send_message":
   $message = new AMFinderMessages;
   $message->loadDataFromRequest();
   $message->codeSender = $_SESSION[user]->codeUser;
   $message->time = time();
   $message->status = AMFinderMessages::ENUM_STATUS_NOT_READ;
   $message->save();
   echo CMHTMLObj::getScript("parent.Finder_clearChatBox();");
   die(note($_REQUEST));
   break;
 case "A_close_chat":
   $_language = $_CMAPP[i18n]->getTranslationArray("finder");

   @session_start(); 

   $_SESSION[finder]->sendMessage($_REQUEST[frm_codeUser], $_language[user_close_window]);
   
   $_SESSION[finder]->stopChat($_REQUEST[frm_codeUser]);
   
   die(note($_REQUEST));
   break;
}




$requests = $_SESSION[finder]->getNewRequests();

if($requests->__hasItems()) {
  foreach($requests as $item) {
    if(!AMFinder::isChatOpen($item->codeSender)) {
      $pag->add("<script type=\"text/javascript\">");
      $pag->add("window.parent.parent.Finder_alertUser($item->codeSender, '$_CMAPP[services_url]', $item->code);</script>");
      $pag->add($item->codeSender." enviou uma mensagem<br>");
    }
  }
}


if($_SESSION[finder]->getFinderTime() < time()-AMFinder::getTimeOut()) {
  $users = $_SESSION[finder]->getOnLineUsers();
  $_SESSION[finder]->setTime();
}

$users = $_SESSION[finder]->getOnLineUsers();
$online = array();

//faz um scan no array de objetos e passa-os para um segundo array.
//Faz isso para poder identificar quando um �nico usu�rios est� conectado em
//duas m�quina diferents e mostra-lo s� uma vez
//A m�quina em que o usu�rio estiver conectado a menos tempo recebe preced�ncia.

if($users->__hasItems()) {
  
  foreach($users as $item) {

    if($item->flagEnded == CMEnvSession::ENUM_FLAGENDED_ENDED) {
      //unset($_SESSION[amadis][finder][contacts][$item->codeUser]);
      continue;
    }

    switch($item->visibility) {

    case CMEnvSession::ENUM_VISIBILITY_VISIBLE://visivel
      $online[$item->codeUser] = 1;
      //$_SESSION[amadis][finder][contacts][$item->codeUser][status] = AMFinder::FINDER_NORMAL_MODE;

      //setar no menu como online
      $pag->add("<script language=\"javascript1.2\">");
      $pag->add("window.parent.parent.Finder_changeUserStatus('UserIco_$item->codeUser','$_CMAPP[url]','online');</script>");
      $pag->add($item->codeUser." onLine<BR>");
      break; 
    case CMEnvSession::ENUM_VISIBILITY_BUSY: //ocupado
      $online[$item->codeUser] = 1;
      //$_SESSION[amadis][finder][contacts][$item->codeUser][status] = AMFinder::FINDER_BUSY_MODE;
      //setar como ocupado
      $pag->add("<script language=\"javascript1.2\">");
      $pag->add("window.parent.parent.Finder_changeUserStatus('UserIco_$item->codeUser','$_CMAPP[url]','busy');</script>");
      $pag->add($item->codeUser." ocupado<br>");
      
      break;
    case CMEnvSession::ENUM_VISIBILITY_HIDDEN: //oculto
      $online[$item->codeUser] = 1;
      //$_SESSION[amadis][finder][contacts][$item->codeUser][status] = AMFinder::FINDER_HIDDEN_MODE;
      $pag->add("<script language=\"javascript1.2\">");
      $pag->add("window.parent.parent.Finder_changeUserStatus('UserIco_$item->codeUser','$_CMAPP[url]','hidden');</script>");
      $pag->add($item->codeUser."oculto<br>");
      break;
    }
    
  }

}

//seta como offline os restante dos usuarios
foreach($_SESSION[amadis][friends] as $item) {
  if(empty($online[$item->codeUser])) {
    $pag->add("<script language=\"javascript1.2\">");
    $pag->add("window.parent.parent.Finder_changeUserStatus('UserIco_$item->codeUser','$_CMAPP[url]','offline');</script>");
  }
}

//echo $_SESSION[finder];  
echo $pag;


?>