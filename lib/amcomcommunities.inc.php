<?php

// Pedro Pimentel - 22/02/2005 - zukunft@gmail.com


class AMComunidadeChats extends CMObj{
  
  public function configure() {
     $this->setTable("comunidadeChats");

     $this->addField("codSala",CMObj::TYPE_INTEGER,11,1,0,0);
     $this->addField("codComunidade",CMObj::TYPE_INTEGER,11,1,0,0);


  }

}

?>