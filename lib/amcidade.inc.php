<?

class AMCidade extends CMObj {

  public function configure() {
    $this->setTable("Cidades");

    $this->addField("codCidade",CMObj::TYPE_INTEGER,11,1,0,1);
    $this->addField("nomCidade",CMObj::TYPE_VARCHAR,100,1,0,0);
    $this->addField("codEstado",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("tempo",CMObj::TYPE_INTEGER,11,1,0,0);

    $this->addPrimaryKey("codCidade");
  }
}



?>