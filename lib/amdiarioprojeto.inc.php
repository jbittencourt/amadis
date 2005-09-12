<?

class AMDiarioProjeto extends CMObj {

   public function configure() {
     $this->setTable("DiarioProjeto");

     $this->addField("codePost",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("codeUser",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("codeProjeto",CMObj::TYPE_INTEGER,20,1,0,0);

     $this->addPrimaryKey("codePost");
     $this->addPrimaryKey("codeProjeto");
  }
}

?>
