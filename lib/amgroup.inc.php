<?

class AMGroup extends CMObj {

   public function configure() {
     $this->setTable("Group");

     $this->addField("codeGroup",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("description",CMObj::TYPE_VARCHAR,100,1,0,0);
     $this->addField("managed",CMObj::TYPE_VARCHAR,1,1,0,0);
     $this->addField("time",CMObj::TYPE_INTEGER,20,1,0,0);

     $this->addPrimaryKey("codeGroup");
  }


  public static function getUserRequest($codeUser,$group) {
    $codeGroup = $group->codeGroup;
    $q = new CMQuery(AMGroupMemberJoin);
    $q->setFilter("codeUser = $codeUser AND codeGroup = $codeGroup");
    $q->setProjection("textRequest");
    $res = $q->execute();
    return $res;    
  }
  
  public static function getGroupResponse($codeUser, $group) {
    $codeGroup = $group->codeGroup;
    $q = new CMQuery(AMGroupMemberJoin);
    $q->setFilter("codeUser = $codeUser AND codeGroup = $codeGroup");
    $q->setProjection("textResponse");
    $res = $q->execute();
    return $res;    
  }
}

?>