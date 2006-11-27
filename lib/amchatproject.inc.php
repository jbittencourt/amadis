<?php
class AMChatProject extends CMObj{
  
  public function configure() {
    $this->setTable("ChatsProject");
    
    $this->addField("codeRoom",CMObj::TYPE_INTEGER,11,1,0,1);
    $this->addField("codeProject",CMObj::TYPE_INTEGER,11,1,0,0);

    $this->addPrimaryKey("codeRoom");

  }

}