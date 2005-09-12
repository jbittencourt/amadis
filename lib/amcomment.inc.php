<?

class AMComment extends CMObj {

   public function configure() {
     $this->setTable("Comentarios");

     $this->addField("codComentario",CMObj::TYPE_INTEGER,6,1,0,1);
     $this->addField("desNome",CMObj::TYPE_VARCHAR,50,1,0,0);
     $this->addField("codeUser",CMObj::TYPE_INTEGER,11,0,0,0);
     $this->addField("desComentario",CMObj::TYPE_BLOB,65535,1,0,0);
     $this->addField("tempo",CMObj::TYPE_INTEGER,11,1,0,0);
     
     $this->addPrimaryKey("codComentario");
  }
}

?>
