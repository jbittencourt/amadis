<?

class ProjectMemberJoin extends CMObj {

   public function configure() {
     $this->setTable("ProjectMemberJoin");

     $this->addField("codeUser",CMObj::TYPE_INTEGER,"20",1,0);
     $this->addField("codeProject",CMObj::TYPE_INTEGER,"20",1,0);
     $this->addField("type",CMObj::TYPE_VARCHAR,"1",1,0);
     $this->addField("status",CMObj::TYPE_VARCHAR,"11",1,0);
     $this->addField("time",CMObj::TYPE_INTEGER,"20",1,0);

     $this->addPrimaryKey("codeUser");
     $this->addPrimaryKey("codeProject");
     $this->addPrimaryKey("time");
  }
}

?>
<?

class ProjectMemberJoin extends CMObj {

const ENUM_STATUS_NOT_ANSERED = "NOT_ANSERED";
const ENUM_STATUS_REJECTED = "REJECTED";
const ENUM_STATUS_ACCEPTED = "ACCEPTED";

   public function configure() {
     $this->setTable("ProjectMemberJoin");

     $this->addField("codeUser",CMObj::TYPE_INTEGER,"20",1,0);
     $this->addField("codeProject",CMObj::TYPE_INTEGER,"20",1,0);
     $this->addField("type",CMObj::TYPE_VARCHAR,"1",1,0);
     $this->addField("status",CMObj::TYPE_ENUM,"11",1,0);
     $this->addField("time",CMObj::TYPE_INTEGER,"20",1,0);

     $this->addPrimaryKey("codeUser");
     $this->addPrimaryKey("codeProject");
     $this->addPrimaryKey("time");

     $this->setEnumValidValues("status",array(ENUM_STATUS_NOT_ANSERED,
                                              ENUM_STATUS_REJECTED,
                                              ENUM_STATUS_ACCEPTED)
                              );
  }
}

?>
