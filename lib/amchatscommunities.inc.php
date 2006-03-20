<?

class AMChatsCommunities extends CMObj{
  
  public function configure() {
     $this->setTable("ChatsCommunities");

     $this->addField("codeRoom",CMObj::TYPE_INTEGER,11,1,0,0);
     $this->addField("codeCommunity",CMObj::TYPE_INTEGER,11,1,0,0);


  }

}

?>