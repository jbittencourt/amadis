<?php


class AMProjectChats extends CMObj{
  
  public function configure() {
     $this->setTable("projetoChats");

     $this->addField("codSala",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("codProjeto",CMObj::TYPE_INTEGER,20,1,0,0);


  }

}