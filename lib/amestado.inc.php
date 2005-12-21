<?


class AMEstado extends CMObj {

  public function configure() {
    $this->setTable("Estados");

    $this->addField("codEstado",CMObj::TYPE_INTEGER,11,1,0,1);
    $this->addField("nomEstado",CMObj::TYPE_VARCHAR,20,1,0,0);
    $this->addField("desPais",CMObj::TYPE_VARCHAR,20,1,0,0);
    $this->addField("desSigla",CMObj::TYPE_VARCHAR,3,1,0,0);

    $this->addPrimaryKey("codEstado");
  }

  public function listaCidades() {
    $q = new CMQuery(AMCidade);
    $q->setFilter("codEstado='$this->codEstado'");
    return $q->execute();
  }


  public function listaEstados(){
    $q = new CMQuery(AMEstado);
    return $q->execute();
  }

}

?>