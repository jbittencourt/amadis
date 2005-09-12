<?

class AMProjectComment extends CMObj {

   public function configure() {
     $this->setTable("ProjetoComentario");

     $this->addField("codProjeto",CMObj::TYPE_INTEGER,11,1,0,0);
     $this->addField("codComentario",CMObj::TYPE_INTEGER,20,1,0,0);

     $this->addPrimaryKey("codProjeto");
     $this->addPrimaryKey("codComentario");
  }
}

?>
