<?php

// Pedro Pimentel - 22/02/2005 - zukunft@gmail.com

class AMCursoParticipante extends CMObj {
  public function configure() {
    $this->setTable("CursoParticipantes");
    
    $this->addField("codeCurso",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("codUser",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("flagCoordenador",CMObj::TYPE_VARCHAR,1,1,0,0);
    $this->addField("flagAutorizado",CMObj::TYPE_VARCHAR,1,1,0,0);
    $this->addField("tempo",CMObj::TYPE_INTEGER,20,1,0,0);
    $this->addField("matriculado",CMObj::TYPE_VARCHAR,1,1,0,0);
    $this->addField("matricula",CMObj::TYPE_INTEGER,20,1,0,1);
    $this->addPrimaryKey("matricula");
}








}
?>