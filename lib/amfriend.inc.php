<?php
/**
 * Model an friend selected by the user.
 *
 * Model an friend selected by the user.
 *
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 **/


class AMFriend extends CMObj {
  
  const ENUM_STATUS_NOT_ANSWERED = "NOT_ANSWERED";
  const ENUM_STATUS_REJECTED = "REJECTED";
  const ENUM_STATUS_ACCEPTED = "ACCEPTED";
  
  public function configure() {
    $this->setTable("Friends");
    
    $this->addField("codeUser",CMObj::TYPE_INTEGER,"20",1,0,0);
    $this->addField("codeFriend",CMObj::TYPE_INTEGER,"20",1,0,0);
    $this->addField("comentary",CMObj::TYPE_BLOB,"255",1,0,0);
    $this->addField("status",CMObj::TYPE_ENUM,"12",1,"NOT_ANSWERED",0);
    $this->addField("time",CMObj::TYPE_INTEGER,"20",1,0,0);
    
    $this->addPrimaryKey("codeUser");
    $this->addPrimaryKey("codeFriend");
  
    $this->setEnumValidValues("status",array(self::ENUM_STATUS_NOT_ANSWERED,
					     self::ENUM_STATUS_REJECTED,
					     self::ENUM_STATUS_ACCEPTED));

  }
}