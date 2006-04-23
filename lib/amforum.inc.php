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

class AMForum extends CMObj implements  CMACLAppInterface {

  const PRIV_ALL = "all";
  const PRIV_VIEW = "view";
  const PRIV_POST = "post";
  const PRIV_DELETE = "delete";
  const PRIV_EDIT = "edit";

  private $cacheACO;

  public function configure() {
     $this->setTable("Forums");

     $this->addField("code",CMObj::TYPE_INTEGER,20,1,0,1);
     $this->addField("name",CMObj::TYPE_VARCHAR,100,1,0,0);
     $this->addField("codeACO",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("creationTime",CMObj::TYPE_INTEGER,20,1,0,0);

     $this->addPrimaryKey("code");
  }

  public function save() {

    if(($this->state==self::STATE_NEW) || ($this->state==self::STATE_DIRTY_NEW) ) {
      $aco = new CMACO($this);
      $aco->description = "Forum ".$this->name;
      $aco->time = time();
      $aco->save();
      
      $this->codeACO = $aco->code;
    }

    parent::save();
  }


  public function listMessages() {

    $q = new CMQuery('AMForumMessage');
    $q->setFilter("codeForum=".$this->code);

    $j = new CMJoin(CMJoin::INNER);
    $j->on("ForumMessages.codeUser=User.codeUser");
    $j->setClass('AMUser');
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
    if(!isset($_SESSION['amadis']['forum']['visits'][$this->code])) {
      $q = new CMQuery('AMForumVisit');
      $q->setFilter("codeForum=".$this->code." AND codeUser=$userCode");
      $q->setLimit(0,1);
      $q->setOrder("time DESC");
      $_SESSION['amadis']['forum']['visits'][$this->code] = $q->execute();

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
    list($k,$r) = each($_SESSION['amadis']['forum']['visits'][$this->code]->items);
    return $r;
  }

  /**
   * Get the ACO of this Forum
   **/
  public function getACO() {
    if(!empty($this->cacheACO)) return $this->cacheACO;
    $this->cacheACO = new CMACO($this);
    $this->cacheACO->code = $this->codeACO;
    try {
      $this->cacheACO->load();
    } catch(CMDBNoRecord $e) {
      Throw new AMException('NO ACO Defined');
    }

    return $this->cacheACO;
  }

  public function listPrivileges() {
    return  array(self::PRIV_ALL, 
		  self::PRIV_POST, 
		  self::PRIV_VIEW,
		  self::PRIV_EDIT,
		  self::PRIV_DELETE);
  }

  public function listPrivilegesMessages() {
    $_lang = $_CMAPP[i18n]->getTranslationArray("forum");
    return array( self::PRIV_ALL=>$_lang['privs_all'],
		  self::PRIV_POST=>$_lang['privs_post'],
		  self::PRIV_VIEW=>$_lang['privs_view'],
		  self::PRIV_EDIT=>$_lang['privs_edit'],
		  self::PRIV_DELETE=>$_lang['privs_delete']);
  }

  /**Get Images from library of a forum
   *
   */
  public static function loadImageLibrary() {

    $lib = new AMuserLibraryEntry($_SESSION['user']->codeUser);
    $lib = $lib->getLibrary($_SESSION['user']->codeUser);

    $q = new CMQuery(AMArquivo);
    
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass(AMLibraryFiles);
    $j->on("filesCode = codeArquivo");
    
    $q->addJoin($j, "lib");
    $q->setProjection("AMArquivo::codeArquivo, AMArquivo::tipoMime, AMArquivo::nome, AMArquivo::metaDados, AMLibraryFiles::*");
    $q->setFilter("libraryCode = $lib AND Arquivo.tipoMime LIKE 'image%'");
    return $q->execute();
  }
  
  public static function loadProjectImageLibrary() {

    $q = new CMQuery(AMLibraryFiles);

    $j = new CMJoin(CMJoin::LEFT);
    $j->setClass(AMProjectLibraryEntry);
    $j->on("AMLibraryFiles::libraryCode = AMProjectLibraryEntry::libraryCode");
    $j->setFake();

    $j2 = new CMJoin(CMJoin::LEFT);
    $j2->setClass(AMArquivo);
    $j2->on("AMArquivo::codeArquivo = AMLibraryFiles::filesCode");
    
    $j3 = new CMJoin(CMJoin::LEFT);
    $j3->setClass(AMProjeto);
    $j3->on("AMProjectLibraryEntry::projectCode = AMProjeto::codeProject");
    
    $j4 = new CMJoin(CMJoin::INNER);
    $j4->setClass('CMGroup');
    $j4->on('AMProjeto::codeGroup=CMGroup::codeGroup');
    $j4->setFake();
    
    $j5 = new CMJoin(CMJoin::LEFT);
    $j5->setClass('CMGroupMember');
    $j5->on('CMGroupMember::codeGroup=CMGroup::codeGroup');
    $j5->setFake();
    
    $q->addJoin($j, "pjlib");
    $q->addJoin($j2, "files");
    $q->addJoin($j3, "proj");
    $q->addJoin($j4, "grupos");
    $q->addJoin($j5, "membros");
    
    $q->setProjection("AMLibraryFiles::filesCode, AMProjeto::title, AMProjeto::codeProject, AMArquivo::codeArquivo, AMArquivo::tipoMime, AMArquivo::metaDados, AMArquivo::nome");
    
    $q->setFilter('CMGroupMember::codeUser = '.$_SESSION['user']->codeUser.' AND CMGroupMember::status="'.CMGroupMember::ENUM_STATUS_ACTIVE.'" AND filesCode != "NULL" AND tipoMime LIKE "image%"');
    
    return $q->execute();

  }
}


?>
