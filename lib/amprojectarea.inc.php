<?php

class AMProjectArea extends CMObj {

  public function configure() {
    $this->setTable("ProjectAreas");

    $this->addField("codeProject",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("codeArea",CMObj::TYPE_INTEGER,9,1,0,0);

    $this->addPrimaryKey("codeProject");
    $this->addPrimaryKey("codeArea");
  }
}