<?

class AMDiarioProfile extends CMObj {

   public function configure() {
     $this->setTable("DiarioProfile");

     $this->addField("codeUser",CMObj::TYPE_INTEGER,11,1,0,0);
     $this->addField("tituloDiario",CMObj::TYPE_VARCHAR,65535,1,0,0);
     $this->addField("textoProfile",CMObj::TYPE_TEXT,65535,1,0,0);
     $this->addField("image",CMObj::TYPE_INTEGER,20,1,0,0);

     $this->addPrimaryKey("codeUser");
  }
}

?>
