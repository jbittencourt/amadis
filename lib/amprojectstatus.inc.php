<?php

class AMProjectStatus extends CMObj {

  public function configure() {
    $this->setTable("ProjectStatus");

    $this->addField("code",CMObj::TYPE_INTEGER,4,1,0,1);
    $this->addField("name",CMObj::TYPE_VARCHAR,40,1,0,0);

    $this->addPrimaryKey("code");
  }
}