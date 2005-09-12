<?

class AMLog extends CMObj {

   public function configure() {
     $this->setTable("Logs");

     $this->addField("code",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("time",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("user",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("class",CMObj::TYPE_VARCHAR,40,1,0,0);
     $this->addField("type",CMObj::TYPE_VARCHAR,40,1,0,0);
     $this->addField("key",CMObj::TYPE_VARCHAR,30,1,0,0);
     $this->addField("message",CMObj::TYPE_VARCHAR,80,1,0,0);

     $this->addPrimaryKey("code");
  }

  
}

?>
