<?

class AMMailMessages extends CMObj {

  const ENUM_STATUS_NOT_READ = "NOT_READ";
  const ENUM_STATUS_READ = "READ";
  const ENUM_STATUS_FORWARD = "FORWARD";
  const ENUM_STATUS_REPLY = "REPLY";

  public function configure() {
    $this->setTable("MailMessages");
    
    $this->addField("code",CMObj::TYPE_INTEGER,11,1,0,1);
    $this->addField("sender",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("addressee",CMObj::TYPE_VARCHAR,11,1,0,0);
    $this->addField("subject",CMObj::TYPE_VARCHAR,100,1,0,0);
    $this->addField("text",CMObj::TYPE_TEXT,65535,1,0,0);
    $this->addField("status",CMObj::TYPE_ENUM,"10",1,"NOT_READ",0);
    $this->addField("time",CMObj::TYPE_INTEGER,11,1,0,0);

    $this->addPrimaryKey("code");

    $this->setEnumValidValues("status",array(self::ENUM_STATUS_NOT_READ,
					     self::ENUM_STATUS_READ,
					     self::ENUM_STATUS_FORWARD,
					     self::ENUM_STATUS_REPLY));
  }


  function listaMensagens ($codeUser){
    $query=new CMQuery(AMCorreioMensagens);
    $query->setFilter("codeUser = $codeUser");
    $result=$query->execute();
    return $result;
  }

  function corpoMensagem ($codeMensagem){
    $query=new CMQuery(AMCorreioMensagens);
    $query->setFilter("codeMensagem = $codeMensagem");
    $result=$query->execute();
    return $result;
  }

}

?>
