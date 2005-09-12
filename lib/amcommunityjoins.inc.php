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

class AMCommunityMemberJoin extends CMObj {

   const ENUM_TYPE_REQUEST = "REQUEST";
   const ENUM_TYPE_INVITATION = "INVITATION";
   const ENUM_STATUS_NOT_ANSWERED = "NOT_ANSWERED";
   const ENUM_STATUS_REJECTED = "REJECTED";
   const ENUM_STATUS_ACEPTED = "ACCEPTED";

   public function configure() {
     $this->setTable("CommunityJoins");

     $this->addField("codeCommunity",CMObj::TYPE_INTEGER,"20",1,0,0);
     $this->addField("code",CMObj::TYPE_INTEGER,"20",1,0,0);
     $this->addField("status",CMObj::TYPE_ENUM,"12",1,"NOT_ANSWERED",0);
     $this->addField("type",CMObj::TYPE_ENUM,"10",1,"REQUEST",0);
     $this->addField("time",CMObj::TYPE_INTEGER,"20",1,0,0);

     $this->addPrimaryKey("code");
     $this->addPrimaryKey("codeCommunity");

     $this->setEnumValidValues("type",array(self::ENUM_TYPE_REQUEST,
                                            self::ENUM_TYPE_INVITATION));
     $this->setEnumValidValues("status",array(self::ENUM_STATUS_NOT_ANSWERED,
                                              self::ENUM_STATUS_ANSWERED,
					      self::ENUM_STATUS_ACEPTED));
  }
}

//put your functions here

?>
