<?php

class AMComment extends CMObj {

   public function configure() {
     $this->setTable("Comments");

     $this->addField("codeComment",CMObj::TYPE_INTEGER,6,1,0,1);
     $this->addField("name",CMObj::TYPE_VARCHAR,50,1,0,0);
     $this->addField("codeUser",CMObj::TYPE_INTEGER,11,0,0,0);
     $this->addField("text",CMObj::TYPE_BLOB,65535,1,0,0);
     $this->addField("time",CMObj::TYPE_INTEGER,11,1,0,0);
     
     $this->addPrimaryKey("codeComment");
  }
}