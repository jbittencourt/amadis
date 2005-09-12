<?

class AMAviso extends CMObj {

   public function configure() {
     $this->setTable("Avisos");

     $this->addField("codeAviso",CMObj::TYPE_INTEGER,20,1,0,1);
     $this->addField("titulo",CMObj::TYPE_VARCHAR,100,1,0,0);
     $this->addField("descricao",CMObj::TYPE_TEXT,65535,1,0,0);
     $this->addField("tempoInicio",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("tempoFim",CMObj::TYPE_INTEGER,20,1,0,0);

     $this->addPrimaryKey("codeAviso");
  }
}

?>
