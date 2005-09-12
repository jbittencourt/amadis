<?
/**
 * Model an friend selected by the user.
 *
 * Model an friend selected by the user.
 *
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 **/


class AMFriend extends CMObj {
  
  public function configure() {
    $this->setTable("Friends");
    
    $this->addField("codeUser",CMObj::TYPE_INTEGER,"20",1,0,0);
    $this->addField("codeFriend",CMObj::TYPE_INTEGER,"20",1,0,0);
    $this->addField("comentary",CMObj::TYPE_BLOB,"255",1,0,0);
    $this->addField("time",CMObj::TYPE_INTEGER,"20",1,0,0);
    
    $this->addPrimaryKey("codeUser");
  
  }
}

//put your functions here

?>
