<?
/**
 * Class that representas a Forum Message.
 *
 * Class that representas a Forum Message.
 *
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 **/

class AMForumMessage extends CMObj {

  public $children=array();

  public function configure() {
    $this->setTable("ForumMessages");

    $this->addField("code",CMObj::TYPE_INTEGER,20,1,0,1);
    $this->addField("codeForum",CMObj::TYPE_INTEGER,20,1,0,0);
    $this->addField("codeUser",CMObj::TYPE_INTEGER,20,1,0,0);
    $this->addField("title",CMObj::TYPE_VARCHAR,100,1,0,0);
    $this->addField("body",CMObj::TYPE_TEXT,"0",1,0,0);
    $this->addField("parent",CMObj::TYPE_INTEGER,20,1,0,0);
    $this->addField("timePost",CMObj::TYPE_INTEGER,20,1,0,0);
    
    $this->addPrimaryKey("code");
  }
}


?>
