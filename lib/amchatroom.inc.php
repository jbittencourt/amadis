<?php

class AMChatRoom extends CMObj{  

  const ENUM_CHAT_TYPE_PROJECT = "PROJECT";
  const ENUM_CHAT_TYPE_COMMUNITY = "COMMUNITY";
  const ENUM_CHAT_TYPE_COURSE = "COURSE";
  const ENUM_CHAT_TYPE_FREE = "FREE";
  
  public function configure() {
     $this->setTable("ChatRoom");

     $this->addField("codeRoom",CMObj::TYPE_INTEGER,11,1,0,1);
     $this->addField("name",CMObj::TYPE_VARCHAR,100,1,0,0);
     $this->addField("description",CMObj::TYPE_VARCHAR,255,1,0,0);
     $this->addField("infinity",CMObj::TYPE_VARCHAR,1,1,0,0);
     $this->addField("beginDate",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("endDate",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("chatType",CMObj::TYPE_ENUM,20,1,"PROJECT",0);
     $this->addField("codeUser",CMObj::TYPE_INTEGER,11,1,0,0);
     $this->addField("time",CMObj::TYPE_INTEGER,20,1,0,0);

     $this->addPrimaryKey("codeRoom");

     $this->setEnumValidValues("chatType",array(self::ENUM_CHAT_TYPE_PROJECT,
						self::ENUM_CHAT_TYPE_COMMUNITY,
						self::ENUM_CHAT_TYPE_COURSE,
						self::ENUM_CHAT_TYPE_FREE));

  }

  public function countRoomUsers() {
    $q = new CMQuery('AMChatConnection');

    $q->setFilter("codeRoom = $this->codeRoom AND flag = '".AMChatConnection::ENUM_FLAG_ONLINE."'");
    $q->setCount();

    return $q->execute();
  }
}