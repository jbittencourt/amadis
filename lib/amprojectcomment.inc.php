<?php

class AMProjectComment extends CMObj {

   public function configure() {
     $this->setTable("ProjectComments");

     $this->addField("codeProject",CMObj::TYPE_INTEGER,11,1,0,0);
     $this->addField("codeComment",CMObj::TYPE_INTEGER,20,1,0,0);

     $this->addPrimaryKey("codeProject");
     $this->addPrimaryKey("codeComment");
  }
}