<?

class AMCommunityForum extends CMObj {


   public function configure() {
     $this->setTable("CommunityForums");

     $this->addField("codeCommunity",CMObj::TYPE_VARCHAR,20,1,0,0);
     $this->addField("codeForum",CMObj::TYPE_VARCHAR,20,1,0,0);

     $this->addPrimaryKey("codeCommunity");
     $this->addPrimaryKey("codeForum");

  }
}
?>