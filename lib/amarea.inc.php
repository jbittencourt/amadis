<?

class AMArea extends CMObj {

  public function configure() {
    $this->setTable("Areas");

    $this->addField("codArea",CMObj::TYPE_INTEGER,4,1,0,1);
    $this->addField("nomArea",CMObj::TYPE_VARCHAR,50,1,0,0);
    $this->addField("codPai",CMObj::TYPE_INTEGER,4,1,0,0);
    $this->addField("intGeracao",CMObj::TYPE_VARCHAR,1,1,0,0);
    $this->addPrimaryKey("codArea");    
  }
  
  function listaProjetos() {
     
     $q = new CMQuery(AMProjeto);
     $q->setNaturalJoin(AMProjetoArea,"temp");
     $q->setFilter("codArea = '$this->codArea'");
     return $q->execute();
   }

  
   function listaFilhas() {
     $q = new CMQuery(AMArea);
     $q->setFilter("codPai = '$this->codArea'");
     return $q->execute();
   }

  public function listaAreas(){
    $q = new CMQuery(AMArea);
    return $q->execute();
  }

    
}



?>