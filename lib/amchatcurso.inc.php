<?php


class AMCursoChats extends CMObj{
  
  public function configure() {
     $this->setTable("cursoChats");

     $this->addField("codSala",CMObj::TYPE_INTEGER,20,1,0,1);
     $this->addField("codCurso",CMObj::TYPE_INTEGER,20,1,0,1);


  }

}

?>