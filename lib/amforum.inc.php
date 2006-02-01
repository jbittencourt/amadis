<?
/**
 * Class that implements the Forums Table
 *
 * The AMForum class is an abstraction of an Discussion forum in 
 * AMADIS. The discussion forum is a tool where users can send
 * messages in an assyncronous way, of a certain topic.
 *
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 **/

class AMForum extends CMObj {


   public function configure() {
     $this->setTable("Forums");

     $this->addField("code",CMObj::TYPE_INTEGER,20,1,0,1);
     $this->addField("name",CMObj::TYPE_VARCHAR,100,1,0,0);
     $this->addField("aco",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("creationTime",CMObj::TYPE_INTEGER,20,1,0,0);

     $this->addPrimaryKey("code");
  }

  public function save() {

    if(($state==self::STATE_NEW) || ($state==self::STATE_DIRTY_NEW) ) {
      $aco = new CMACO;
      $aco->description = "Forum ".$this->name;
      $aco->time = time();
      $aco->save();
    }

    $this->aco = $aco->code;
    parent::save();
  }


  public function listMessages() {

    $q = new CMQuery(AMForumMessage);
    $q->setFilter("codeForum=".$this->code);

    $j = new CMJoin(CMJoin::INNER);
    $j->on("ForumMessages.codeUser=User.codeUser");
    $j->setClass(AMUser);
    $q->addJoin($j,"user");

    return $q->execute();

  }


  public function listMessagesAsTree() {
    $cont = $this->listMessages();


    $messages = array();

    foreach($cont as $item) {
      $messages[$item->code] = clone $item;
    }

    $root_messages = array();
    
    foreach($messages as $message) {
      if($message->parent==0) {
	$root_messages[$message->code] =  $message; //the use of the clone method is necessary.
	                                            //otherwise the object will be destroyed at the end of the function
      }
      else {
	$messages[$message->parent]->children[$message->code] = $message;
      }
    }

    return $root_messages;
  }

  function lastVisit($userCode) {
    if(!isset($_SESSION[amadis][forum][visits][$this->code])) {
      $q = new CMQuery(AMForumVisit);
      $q->setFilter("codeForum=".$this->code." AND codeUser=$userCode");
      $q->setLimit(0,1);
      $q->setOrder("time DESC");
      $_SESSION[amadis][forum][visits][$this->code] = $q->execute();

      $visit = new AMForumVisit;
      $visit->codeForum = $this->code;
      $visit->codeUser = $userCode;
      $visit->time = time();
      try {
	$visit->save();
      } catch(CMDBException $e) {
	throw $e;
      }
    }
    list($k,$r) = each($_SESSION[amadis][forum][visits][$this->code]->items);
    return $r;
  }

  /**
   * Get the ACO of this Forum
   **/
  public function getACO() {
    $aco = new CMACO;
    $aco->code = $this->codeACO;
    try {
      $aco->load();
    } catch(CMDBNoRecord $e) {
      Throw new AMException('NO ACO Defined');
    }

    return $aco;
  }

}

?>
