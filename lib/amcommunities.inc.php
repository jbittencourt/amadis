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

  public function isMember($codeUser) {
    
    $q = new CMQuery(AMCommunityMembers);
    $q->setFilter("codeUser = $codeUser AND codeCommunity = $this->code");
    $res = $q->execute();
    
    if($res->__hasItems()) return true;
    else return false;

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


}



?>
