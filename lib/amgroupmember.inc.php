<?

class AMGroupMember extends CMObj {

   public function configure() {
     $this->setTable("GroupMember");

     $this->addField("codeGroup",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("codeUser",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("status",CMObj::TYPE_VARCHAR,11,1,0,0);
     $this->addField("time",CMObj::TYPE_INTEGER,20,1,0,0);

     $this->addPrimaryKey("codeGroup");
     $this->addPrimaryKey("codeUser");
  }
}

?>