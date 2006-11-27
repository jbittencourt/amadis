<?php

class AMChatConnection extends CMObj {

  const ENUM_FLAG_ONLINE = "ONLINE";
  const ENUM_FLAG_OFFLINE = "OFFLINE";

  public function configure(){

    $this->setTable("ChatConnectedUsers");

    $this->addField("codeConnect",CMObj::TYPE_INTEGER,20,1,0,1);
    $this->addField("codeRoom",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("codeUser",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("entranceDate",CMObj::TYPE_INTEGER,20,1,0,0);
    $this->addField("exitDate",CMObj::TYPE_INTEGER,20,1,0,0);
    $this->addField("flag",CMObj::TYPE_ENUM,10,1,"ONLINE",0);

    $this->addPrimaryKey("codeConnect");

    $this->setEnumValidValues("flag",array(self::ENUM_FLAG_ONLINE,
					   self::ENUM_FLAG_OFFLINE));
    
  }


  static function enterRoom($codeRoom) {
    
    $connection = new AMChatConnection;
    
    $connection->codeRoom = $codeRoom;
    $connection->codeUser = $_SESSION['user']->codeUser;
    $connection->entranceDate = time();
    $connection->flag = self::ENUM_FLAG_ONLINE;
    $connection->save();
    
    return $connection->codeConnect;
    
  }

  static public function leaveRoom($codeRoom, $codeConnect, $text) {
    
    $connection = new AMChatConnection;
    $connection->codeUser = $_SESSION['user']->codeUser;
    $connection->codeRoom = $codeRoom;
    $connection->codeConnect = $codeConnect;
    $conexao->flag = self::ENUM_FLAG_ONLINE;
    try {
      $connection->load();
      $connection->exitDate = time();
      $connection->flag = self::ENUM_FLAG_OFFLINE;
      try {
	$connection->save();
	AMChatMessages::sendMessage($codeRoom, 0, $text);
      }catch(CMException $e) {
	die($e->getMessage());
      }
    } catch(CMDBNoRecord $e){
      die($e->getMessage());
    }
    
  }

  
  static function getConnectedUsers($codeRoom) {

    $q = new CMQuery('AMChatConnection');
    
    $q->setProjection("AMChatConnection::codeUser, AMUser::username");

    $q->setFilter("codeRoom = ".$codeRoom." AND flag = '".self::ENUM_FLAG_ONLINE."'");

    $j = new CMJoin(CMJoin::NATURAL);
    $j->setClass('AMUser');
    
    $q->addJoin($j, "user");
    
    return $q->execute();

    
  } 

}