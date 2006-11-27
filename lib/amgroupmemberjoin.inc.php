<?php

class AMGroupMemberJoin extends CMObj {

   public function configure() {
     $this->setTable("GroupMemberJoin");

     $this->addField("codeGroupMemberJoin",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("codeUser",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("codeGroup",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("type",CMObj::TYPE_VARCHAR,1,1,0,0);
     $this->addField("status",CMObj::TYPE_VARCHAR,11,1,0,0);
     $this->addField("textRequest",CMObj::TYPE_VARCHAR,255,1,0,0);
     $this->addField("textResponse",CMObj::TYPE_VARCHAR,255,1,0,0);
     $this->addField("ackResponse",CMObj::TYPE_VARCHAR,1,1,0,0);
     $this->addField("timeResponse",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("codeUserResponse",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("time",CMObj::TYPE_INTEGER,20,1,0,0);

     $this->addPrimaryKey("codeGroupMemberJoin");
  }
}