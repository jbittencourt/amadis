<?
/**
 * Model an invitation or request to be member of a project.
 *
 * There is three ways to become member of a project: the first one is to
 * create the project, the other is to request it and the last is to be
 * invited by some project member. This class models the last two ones.
 * To do this there is two special properties. The type propertie tell
 * if an record is an invitation, done by a project member, or a request,
 * done by the user. The status field, say if the invitation/request has
 * alredy been answered (REJECTED,ACCPETED) or not (NOT_ANSWERED).
 *
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 **/

class AMCommunities extends CMObj {

   const ENUM_FLAGAUTH_ALLOW = "ALLOW";
   const ENUM_FLAGAUTH_REQUEST = "REQUEST";
   const ENUM_STATUS_NOT_AUTHORIZED = "NOT_AUTHORIZED";
   const ENUM_STATUS_AUTHORIZED = "AUTHORIZED";

   public function configure() {
     $this->setTable("Communities");

     $this->addField("code",CMObj::TYPE_INTEGER,"20",1,0,0);
     $this->addField("description",CMObj::TYPE_VARCHAR,"255",1,0,0);
     $this->addField("name",CMObj::TYPE_VARCHAR,"30",1,0,0);
     $this->addField("codeGroup",CMObj::TYPE_INTEGER,"20",1,0,0);
     $this->addField("status",CMObj::TYPE_ENUM,"12",1,"NOT_AUTHORIZED",0);
     $this->addField("flagAuth",CMObj::TYPE_ENUM,"10",1,"ALLOW",0);
     $this->addField("image",CMObj::TYPE_INTEGER,"20",1,0,0);
     $this->addField("time",CMObj::TYPE_INTEGER,"20",1,0,0);

     $this->addPrimaryKey("code");

     $this->setEnumValidValues("flagAuth",array(self::ENUM_FLAGAUTH_ALLOW,
                                            self::ENUM_FLAGAUTH_REQUEST));
     $this->setEnumValidValues("status",array(self::ENUM_STATUS_NOT_AUTHORIZED,
                                              self::ENUM_STATUS_AUTHORIZED));
  }

  /** 
   * Before save the community creates a group and relates it to 
   * the new community
   **/
  public function save() {
    if($this->state==self::STATE_NEW) {
      //create a new group for the project
      $group = new CMGroup;
      $group->description = "Community ".$this->name;
      $group->managed = CMGroup::ENUM_MANAGED_MANAGED;
      $group->time = time();
      try {
	$group->save();
      } catch(CMDBException $e) {
	Throw new AMException("An error ocurred creating the project group.");
      }
      $this->codeGroup = $group->codeGroup;
    }
    
    parent::save();
  }


  public function isAdmin($codeUser) {

    $q = new CMQuery(AMCommunityMembers);
    $q->setFilter("codeUser=$codeUser AND codeCommunity=".$this->code." AND flagAdmin = '".AMCommunityMembers::ENUM_FLAGADMIN_ADMIN."'");
    $res = $q->execute();

    if($res->__hasItems()) 
      return true;
    else 
      return false;

  }
  
  static function listAvaiableStatus() {
    $q = new CMQuery('AMCommunityStatus');
    $res = $q->execute();
    return $res;    
  }
  
  public function listMembers($ini=0, $lenght=5) {
    $q = new CMQuery(AMUser);
    
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass(AMCommunityMembers);
    $j->on("User.codeUser = CommunityMembers.codeUser");

    $q->addJoin($j, "users");
    $q->setLimit($ini, $lenght);
    $q->setFilter("CommunityMembers.codeCommunity = $this->code AND CommunityMembers.flagAdmin = '".AMCommunityMembers::ENUM_FLAGADMIN_MEMBER."'");
    return $q->execute();
  }

  public function listProjects($ini=0, $lenght=5) {
    $q = new CMQuery(AMProjeto);
    
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass(AMCommunityProjects);
    $j->on("AMProjeto::codeProject = AMCommunityProjects::codeProject");

    $q->addJoin($j, "projects");
    $q->setLimit($ini, $lenght);
    $q->setFilter("AMCommunityProjects::codeCommunity = $this->code");
    return $q->execute();
  }

  public function listNews($ini=0, $lenght=5) {
    $q = new CMQuery(AMCommunityNews);
    
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass(AMCommunities);
    $j->on("CommunityNews.codeCommunity = Communities.code");

    $j1 = new CMJoin(CMJoin::INNER);
    $j1->setClass(AMuser);
    $j1->on("CommunityNews.codeUser = User.codeUser");

    $q->addJoin($j, "community");
    $q->addJoin($j1, "users");
    $q->setLimit($ini, $lenght);
    $q->setFilter("CommunityNews.codeCommunity = $this->code");
    return $q->execute();
  }

  
  public function listForums() {
    $q = new CMQuery('AMForum');
    
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass('AMCommunityForum');
    $j->on("AMForum::code = AMCommunityForum::codeForum");
    
    $j2 = new CMJoin(CMJoin::LEFT);
    $j2->on("AMForum::code=AMForumMessage::codeForum");
    $j2->setClass('AMForumMessage');

    $q->addJoin($j, "community");
    $q->addJoin($j2, "messages");
    $q->setFilter("codeCommunity=$this->code");
    
    $q->groupby("AMForum::code");
    $q->addVariable("numMessages","count( AMForumMessage::code )");
    $q->addVariable("lastMessageTime","max( AMForumMessage::timePost )");

    return $q->execute();
  }


  public function ListaMatriculas($lista="",$ini=0,$lenght=5){
    switch($lista){
    case "negadas":
      $q = new CMQuery(AMUser);
      $j = new CMJoin(CMJoin::INNER);
      $j->setClass(AMCommunityMemberJoin);
      $j->on("User.codeUser = CommunityMemberJoin.codeUser");
      
      $q->addJoin($j, "users");
      $q->setLimit($ini, $lenght);
      $q->setFilter("CommunityMemberJoin.codeCommunity = $this->code AND CommunityMemberJoin.status = '".AMCommunityMemberJoin::ENUM_STATUS_REJECTED."'");
      return $q->execute();
    
      break;
    
    case "aceitas":
      $q = new CMQuery(AMUser);
      $j = new CMJoin(CMJoin::INNER);
      $j->setClass(AMCommunityMembers);
      $j->on("User.codeUser = CommunityMembers.codeUser");
      
      $q->addJoin($j, "community");
      $q->setLimit($ini, $lenght);
      $q->setFilter("CommunityMembers.codeCommunity = $this->code AND CommunityMembers.flagAdmin = '".AMCommunityMembers::ENUM_FLAGADMIN_MEMBER."'");

      return $q->execute();
      
      break;
      
    default:
      $q = new CMQuery(AMUser);
      $j = new CMJoin(CMJoin::INNER);
      $j->setClass(AMCommunityMemberJoin);
      $j->on("User.codeUser = CommunityMemberJoin.codeUser");
      
      $q->addJoin($j, "users");
      $q->setLimit($ini, $lenght);
      $q->setFilter("CommunityMemberJoin.codeCommunity = $this->code AND CommunityMemberJoin.status = '".AMCommunityMemberJoin::ENUM_STATUS_NOT_ANSWERED."'");
      return $q->execute();

      break;
    }
    
  }


  /*********************
   * Group managment
   ********************/
  public function getGroup() {
    if(empty($_SESSION[AMADIS][Community][$this->code][group])) {
      $g = new CMGroup;
      $g->codeGroup = $this->codeGroup;
      $g->load();
      $_SESSION[AMADIS][Community][$this->code][group] = $g;
    }  else {
      $g = $_SESSION[AMADIS][Community][$this->code][group];
    }
    return $g;
  }

  public function getOpenRooms() {
    $q = new CMQuery('AMChatRoom');

    $j = new CMJoin(CMJoin::NATURAL);
    $j->setClass('AMChatsCommunities');

    $q->addJoin($j, "room");

    $time = time();
    $q->setFilter("chatType='".AMChatRoom::ENUM_CHAT_TYPE_COMMUNITY."' AND codeCommunity = ".$this->code." AND beginDate <= $time AND endDate > $time");

    return $q->execute();
   
  }

  public function getMarkedChats() {
    
    $q = new CMQuery('AMChatRoom');
    
    $j = new CMJoin(CMJoin::NATURAL);
    $j->setClass('AMChatsCommunities');
    $j->fake = true;

    $j1 = new CMJoin(CMJoin::INNER);
    $j1->setClass('AMUser');
    $j1->on("AMUser::codeUser = AMChatRoom::codeUser");

    $q->addJoin($j1, "user");
    $q->addJoin($j, "aux");
    
    $q->setProjection("ChatRoom.*, User.name");
    $q->groupBy("AMChatRoom::codeRoom");
    $q->setFilter("beginDate > ".time()." AND codeCommunity = $this->code");

    return $q->execute();


  }


}

?>
