<?

class AMArquivo extends CMObj {

   public function configure() {
     $this->setTable("Arquivo");

     $this->addField("codeArquivo",CMObj::TYPE_INTEGER,11,1,0,1);
     $this->addField("dados",CMObj::TYPE_BLOB,16777215,1,0,0);
     $this->addField("tipoMime",CMObj::TYPE_VARCHAR,100,1,0,0);
     $this->addField("tamanho",CMObj::TYPE_INTEGER,11,1,0,0);
     $this->addField("nome",CMObj::TYPE_VARCHAR,30,1,0,0);
     $this->addField("metaDados",CMObj::TYPE_VARCHAR, 255,1,0,0);
     $this->addField("tempo",CMObj::TYPE_INTEGER,11,1,0,0);

     $this->addPrimaryKey("codeArquivo");
  }


  public function loadFileFromRequest($formName) {
    $this->nome = $_FILES[$formName]['name'];
    $this->tipoMime = $_FILES[$formName]['type'];
    $this->tamanho = $_FILES[$formName]['size'];
    $this->tempo = time();

    $this->dados  = implode("",file($_FILES[$formName]['tmp_name']));

    $d = $this->dados;
    if(empty($d)) {
      Throw new AMException("Nao consegui ler o arquivo ".$_FILES[$formName]['tmp_name'].".");
    }

  }

  public function save() {
    $this->dados = addslashes($this->dados);
    parent::save();
  }


}

?>
