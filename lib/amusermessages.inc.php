<?php

class AMUserMessages extends CMObj {

   public function configure() {
     $this->setTable("UserMessages");

     $this->addField("code",CMObj::TYPE_INTEGER,11,1,0,1);
     $this->addField("message",CMObj::TYPE_VARCHAR,255,1,0,0);
     $this->addField("codeUser",CMObj::TYPE_INTEGER,20,0,0,0);
     $this->addField("codeTo",CMObj::TYPE_INTEGER,20,0,0,0);
     $this->addField("time",CMObj::TYPE_INTEGER,11,1,0,0);
     
     $this->addPrimaryKey("code");
  }
}