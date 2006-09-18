<?php

class AMProjectArea extends CMObj {

  public function configure() {
    $this->setTable("ProjetoAreas");

    $this->addField("codProjeto",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("codArea",CMObj::TYPE_INTEGER,9,1,0,0);

    $this->addPrimaryKey("codProjeto");
    $this->addPrimaryKey("codArea");
  }
}