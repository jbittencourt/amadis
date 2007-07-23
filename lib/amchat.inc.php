<?php

class AMChat implements AMAjax {

  public function verifyNameExists($name){
    $q = new CMQuery('AMChatRoom');
    $q->setFilter("name = '$name'");
    
    $res =  $q->execute();
    
    if ($res->__hasItems()) return 1;
    else return 0;
    
  }

  public function createChatRoom($param) {
    $name = $param[0];
    $subject = $param[1];
    $beginDate = $param[2];
    $endDate = $param[3];
    $infinity = $param[4];
    $type = $param[5];
    $msg = $param[6];
    $err = $param[7];
    $code = $param[8];

    $ret = array();
    
    if($this->verifyNameExists($name)) {
      $ret['error'] = "repeated_name";
      $box = new AMAlertBox(AMAlertBox::ERROR, $err);
      $ret['msg'] = $box->__toString();
      return $ret;
    }

    $dFactor = 3600; //esta eh a diferenca entre o timestamp do javascript e do php
    $chatRoom = new AMChatRoom;
    $chatRoom->name = $name;
    $chatRoom->description = $subject;
    $chatRoom->chatType = $type;
    $chatRoom->codeUser = $_SESSION['user']->codeUser;
    $chatRoom->time = time();

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

      switch($type) {
      case AMChatRoom::ENUM_CHAT_TYPE_PROJECT:
	try{
	  $rel = new AMChatsProject;      
	  $rel->codeRoom = $chatRoom->codeRoom;
	  $rel->codeProject = $code;
	  $rel->save();
	}catch(CMException $e) {
	  $chatRoom->delete();
	  $ret['error'] = "not_saved";
	  $box = new AMAlertBox(AMAlertBox::ERROR, $err."<br />ChatProject::".$e->getMessage());
	}
	break;

      case AMChatRoom::ENUM_CHAT_TYPE_COMMUNITY:
	try{
	  $rel = new AMChatsCommunities;      
	  $rel->codeRoom = $chatRoom->codeRoom;
	  $rel->codeCommunity = $code;
	  $rel->save();
	}catch(CMException $e) {
	  $chatRoom->delete();
	  $ret['error'] = "not_saved";
	  $box = new AMAlertBox(AMAlertBox::ERROR, $err."<br />ChatCommunity::".$e->getMessage());
	}
	break;
      }
    }catch(CMException $e) {
      $ret['error'] = "not_saved";
      $box = new AMAlertBox(AMAlertBox::ERROR, $err."<br />ChatRoom::".$e->getMessage());
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
	//$msg .= CMHTMLObj::getScript("for(var i in window) document.write(window[i]+'<br />');");
	$return[] = $msg;
      }
    } else return 0;
    return $return;
  }

  public function xoadGetMeta() {
    $methods = array('verifyNameExists', 'createChatRoom', 'leaveRoom', 'getNewMessages');
    XOAD_Client::mapMethods($this, $methods);
    
    XOAD_Client::publicMethods($this, $methods);
  }

}