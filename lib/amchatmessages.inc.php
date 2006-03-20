<?php

class AMChatMessages extends CMObj {

  public function configure(){

    $this->setTable("ChatMessages");

    $this->addField("codeMessage",CMObj::TYPE_INTEGER,20,1,0,1);
    $this->addField("codeRoom",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("codeSender",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("codeRecipient",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("message",CMObj::TYPE_TEXT,'',1,0,0);
    $this->addField("userStyle",CMObj::TYPE_VARCHAR,30,1,0,0);
    $this->addField("time",CMObj::TYPE_INTEGER,20,1,0,0);

    $this->addPrimaryKey("codeMessage");

  }
 
  static public function sendMessage($codeRoom, $codeRecipient, $text) {

    $message = new AMChatMessages;
    $message->codeRoom = $codeRoom;
    $message->codeSender = $_SESSION['user']->codeUser;
    $message->codeRecipient = $codeRecipient;
    $message->userStyle = $_SESSION['amadis']['chat']['color'];
    $message->time = time();
    
    $message->message = strip_tags($text,"<br><b><i><u>");
    
    $message->save();

  }  
}
?>