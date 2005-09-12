<?php
// criado por Pedro Pimentel
// estah OK

class AMChatMensagem extends CMObj {

  public function configure(){
    $this->setTable("chat_mensagens");
    $this->addField("codMensagem",CMObj::TYPE_INTEGER,20,1,0,1);
    $this->addField("codSalaChat",CMObj::TYPE_INTEGER,9,1,0,0);
    $this->addField("codRemetente",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("codDestinatario",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("tempo",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("desMensagem",CMObj::TYPE_BLOB,65535,1,0,0);
    $this->addField("desTag",CMObj::TYPE_VARCHAR,20,1,0,0);

    $this->addPrimaryKey("codMensagem");
  }

  
  
}
?>