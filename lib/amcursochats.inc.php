<?php

// Pedro Pimentel - 22/02/2005 - zukunft@gmail.com


class AMCursoChats extends CMObj{
  
  public function configure() {
     $this->setTable("cursoChats");

     $this->addField("codSala",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("codCurso",CMObj::TYPE_INTEGER,20,1,0,0);


  }

}

?>