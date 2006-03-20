<?php
class AMChat {

  public function verifyNameExists($name){
    $q = new CMQuery('AMChatRoom');
    $q->setFilter("name = '$name'");
    
    $res =  $q->execute();
    
    if ($res->__hasItems()) return 1;
    else return 0;
    
  }

  public function createChatRoom($name, $subject, $beginDate, $endDate, $infinity, $type, $msg, $err, $codeProject) {

    $dFactor = 3600; //esta eh a diferenca entre o timestamp do javascript e do php
    $chatRoom = new AMChatRoom;
    $chatRoom->name = $name;
    $chatRoom->description = $subject;
    $chatRoom->chatType = $type;
    $chatRoom->codeUser = $_SESSION['user']->codeUser;
    $chatRoom->time = time();

    $ret = array();

    $chatRoom->infinity = ($infinity ? "1" : "0");

    if($beginDate != 0 && $endDate == 0 && $infinity == true) {
      $chatRoom->beginDate = $beginDate-$dFactor;
      $chatRoom->endDate = $endDate-$dFactor;
      $ret['type'] = "scheduled";
    } else if($beginDate != 0 && $endDate != 0) {
      $chatRoom->beginDate = $beginDate-$dFactor;
      $chatRoom->endDate = $endDate-$dFactor;
      $ret['type'] = "scheduled";
    } else { 
      $chatRoom->beginDate = time();
      $chatRoom->endDate = $chatRoom->beginDate+($dFactor*2);
      $ret['type'] = "no_scheduled";
    }

    try {
      $chatRoom->save();
      $ret['error'] = "saved";
      $box = new AMAlertBox(AMAlertBox::MESSAGE, $msg);

      try{
	$rel = new AMChatsProject;      
	$rel->codeRoom = $chatRoom->codeRoom;
	$rel->codeProject = $codeProject;
	$rel->save();
      }catch(CMException $e) {
	$chatRoom->delete();
	$ret['error'] = "not_saved";
	$box = new AMAlertBox(AMAlertBox::ERROR, $err."<br>ChatProject::".$e->getMessage());
      }

    }catch(CMException $e) {
      $ret['error'] = "not_saved";
      $box = new AMAlertBox(AMAlertBox::ERROR, $err."<br>ChatRoom::".$e->getMessage());
    }
    
    $ret['msg'] = $box->__toString();

    $date = getDate($chatRoom->beginDate);
    
    $ret['obj'] = array("code"=>$chatRoom->codeRoom, "name"=>$chatRoom->name, "subject"=>$chatRoom->description, "init"=>$date);

    return $ret;
  }
  
  public function leaveRoom($codeRoom, $codeConnection, $text) {
    AMChatConnection::leaveRoom($codeRoom, $codeConnection, $text);
  }
  
  public function getNewMessages($codeRoom) {
    
    $q = new CMQuery('AMChatMessages');
    $q->setProjection("AMChatMessages::*, AMUser::username");
    
    $q->setFilter("AMChatMessages::codeRoom = $codeRoom AND AMChatMessages::time > ".$_SESSION['amadis']['chat'][$codeRoom]['lastRequest']);
    
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass("AMUser");
    $j->on("AMUser::codeUser = AMChatMessages::codeSender");
    
    $q->addJoin($j, "sender");
    
    $result = $q->execute();
    $return = array();

    $_SESSION['amadis']['chat'][$codeRoom]['lastRequest'] = time();

    if($result->__hasItems()) {
      foreach($result as $item) {
	$talkto = ($item->codeRecipient==0 ? "{ALL}" : $item->codeRecipient );
	$message = new AMSmileRender($item->message);
	$message = $message->__toString();
	$msg = "<div class='messagebox' id='messageBox'>";
	$msg .= "<div class='left $item->userStyle'>";
	$msg .= "<a href='#' class='perfil'>".$item->sender[0]->username."</a> <em> {TALKTO} </em> $talkto</div>\n";
	$msg .= "<div class='right ".$item->userStyle."'>$message</div>";
	$msg .= "</div>\n";
	$linesNumber = (strlen($message)*2)+100;
	//$msg .= CMHTMLObj::getScript("window.scrollTela($linesNumber);");
	//$msg .= CMHTMLObj::getScript("for(var i in window) document.write(window[i]+'<br>');");
	$return[] = $msg;
      }
    } else return 0;
    return $return;
  }  
}  

//   function isLoggedChat($codUser){
//     $sala = $this->codSala;
//     $sql = "codSala=$sala AND codUser=$codUser AND flaOnline=1";
//     $q = new CMQuery(AMChatConnection);
//     $q->setFilter($sql);
//     $res = $q->execute();
//     if ($res->__hasItems()){
//       return TRUE;
//     }else{
//       return FALSE;
//     }
    
//   }

//   public function getName($tipo,$code){
    
//     switch($tipo){
//     case "Projeto":
//       $sql = "codeProject=$code";
//       $q = new CMQuery(AMProjeto);
//       $q->setFilter($sql);
//       $ret = $q->execute();
//       $ret = $ret->items[$code]->title;
      
//       break;

//     case "Comunidade":
//       $sql = "code=$code";
//       $q = new CMQuery(AMCommunities);
//       $q->setFilter($sql);
//       $ret = $q->execute();
//       $ret = $ret->items[$code]->name;
      
//       break;

//     }
//     return $ret;
//   }



//   }

//   public function getInfo($tipo,$code){
    
//     switch($tipo){

//     case "Comunidade":
//       $sql = "code=$code";
//       $q = new CMQuery(AMCommunities);
//       $q->setFilter($sql);
//       $ret = $q->execute();
//       break;

//     case "Projeto":
//       $sql = "codeProject=$code";
//       $q = new CMQuery(AMProjeto);
//       $q->setFilter($sql);
//       $ret = $q->execute();
//       break;

//     }
//     return $ret;
//   }


//   function setTimeOut($time) {
//     $this->timeOut = $time;
    
//   }
  
//   function is_user_in_chatroom($user,$salas) {
   
//     foreach($salas as $sala){
//       $sql  ="codUser=$user AND codSala=".$sala->codSala." AND flaOnline=1 ";
//       $query = new CMQuery(AMChatConnection);
//       $query->setFilter($sql);
//       $res = $query->execute();
//     }
    
//     if ($res->__hasItens()){//count($res->items)>0){
//       return TRUE;
//     }
//     else{
//       return FALSE;
//     } 
    


//   } 


/** ver mais tarde para listar as mensagens com os nomes dos usuarios em uma unica consulta
 *
 */
// $q = new CMQuery('AMChatMessages');
// $q->setProjection("AMChatMessages::*, AMUser::username");

// $q->setFilter("AMChatMessages::codeRoom = 7 AND AMChatMessages::time > 1136378044");

// $j = new CMJoin(CMJoin::INNER);
// $j->setClass("AMUser");
// $j->on("AMUser::codeUser = AMChatMessages::codeSender");

// $j1 = new CMJoin(CMJoin::LEFT, "Rec");
// $j1->setClass("AMUser");
// $j1->on("AMUser::codeUser = AMChatMessages::codeRecipient");

// $q->addJoin($j, "sender");
// $q->addJoin($j1, "recipient");

// $a = $q->execute();
// foreach($a as $item) {
//   note($item);die();
// }

// notelastquery();die();





?>