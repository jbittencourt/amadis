<?php
// criada por Pedro Pimentel
// estah OK, aguarda teste final

class AMChatConnection extends CMObj {


  public function configure(){
    $this->setTable("chat_sala_conectados");

    $this->addField("codConexao",CMObj::TYPE_INTEGER,20,1,0,1);
    $this->addField("codSala",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("codUser",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("datEntrou",CMObj::TYPE_INTEGER,20,1,0,0);
    $this->addField("datSaiu",CMObj::TYPE_INTEGER,20,1,0,0);
    $this->addField("flaOnline",CMObj::TYPE_VARCHAR,1,1,0,0);

    $this->addPrimaryKey("codConexao");
  }


  public function ListaUsuariosConectados($sala){
    $sql = "codSala=".$sala." AND flaOnline=1";
    $query = new CMQuery(AMChatConnection);
    $query->setFilter($sql);
    $res = $query->execute();

    return $res;
  }


}


?>