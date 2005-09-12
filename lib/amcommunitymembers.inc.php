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

class AMCommunityMembers extends CMObj {

   const ENUM_FLAGADMIN_ADMIN = "ADMIN";
   const ENUM_FLAGADMIN_MEMBER = "MEMBER";

   public function configure() {
     $this->setTable("CommunityMembers");

     $this->addField("codeCommunity",CMObj::TYPE_INTEGER,"20",1,0,0);
     $this->addField("codeUser",CMObj::TYPE_INTEGER,"20",1,0,0);
     $this->addField("flagAdmin", CMObj::TYPE_ENUM,"12",1,"MEMBER",0);
     $this->addField("time",CMObj::TYPE_INTEGER,"11",1,0,0);

     $this->addPrimaryKey("codeUser");
     $this->addPrimaryKey("codeCommunity");

     $this->setEnumValidValues("flagAdmin",array(self::ENUM_FLAGADMIN_ADMIN,
                                            self::ENUM_FLAGADMIN_MEMBER));


  }
}

//put your functions here

?>
