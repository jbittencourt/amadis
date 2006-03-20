<?


class AMUser extends CMUser {

  const RPASS_LOWERCASE=1;
  const RPASS_LOWERCASE_NUMBERS=2;
  const RPASS_LOWERCASE_UPPERCASE_NUMBERS=3;
  const RPASS_LOWERCASE_NUMBERS_N=4;
  const RPASS_NUMBERS=5;

  public function configure() {
    parent::configure();

    //estes sao campos especificos do AMUser que nao estao presentes 
    //no CMUser
    $this->addField("email",CMObj::TYPE_VARCHAR,100,1,0,0);
    $this->addField("endereco",CMObj::TYPE_VARCHAR,150,1,0,0);
    $this->addField("codCidade",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("cep",CMObj::TYPE_VARCHAR,9,1,0,0);
    $this->addField("telefone",CMObj::TYPE_VARCHAR,15,1,0,0);
    $this->addField("aboutMe",CMObj::TYPE_TEXT,65535,1,0,0);
    $this->addField("url",CMObj::TYPE_VARCHAR,150,1,0,0);
    $this->addField("datNascimento",CMObj::TYPE_INTEGER,20,1,0,0);

    //in the database, exists an default photo, wich code is 1, so default foto is 1.
    $this->addField("foto",CMObj::TYPE_INTEGER,20,1,1,0);
  }

  function isLoggedChat($codSala){
    $user = $this->codeUser;
    $sql = "codSala=$codSala AND codUser=$user AND flaOnline=1";
    $q = new CMQuery('AMChatConnection');
    $q->setFilter($sql);
    $res = $q->execute();


    if ($res->__hasItems()){
      return TRUE;
    }else{
      return FALSE;
    }
  }
 
  
  function getUserProjectChats() {
    $q = new CMQuery('AMChat');
    
    $j1 = new CMJoin(CMJoin::INNER);
    $j1->setClass('AMProjetoChats');
    $j1->on("AMProjetoChats::codSala = AMChat::codSala");
    $j1->setFake();

    $j2 = new CMJoin(CMJoin::INNER);
    $j2->setClass('AMProjeto');
    $j2->on("AMProjeto::codeProject = AMProjetoChats::codProjeto"); 
    $j2->setFake();
    
    $j3 = new CMJoin(CMJoin::INNER);
    $j3->setClass('CMGroupMember');
    $j3->on("CMGroupMember::codeGroup=AMProjeto::codeGroup");
    $j3->setFake();


    $q->setFilter("CMGroupMember::codeUser = $this->codeUser");

    $q->addJoin($j1,"j1");
    $q->addJoin($j2,"j2");
    $q->addJoin($j3,"j3");

    return $q->execute();

  }

  function getUserCommunityChats(){
    $q = new CMQuery('AMChat');
    
    $j1 = new CMJoin(CMJoin::INNER);
    $j1->setClass('AMCommunitiesChat');
    $j1->on("AMCommunitiesChat::codSala = AMChat::codSala");
    $j1->setFake();

    $j3 = new CMJoin(CMJoin::INNER);
    $j3->setClass('CMGroupMember');
    $j3->on("CMGroupMember::codeGroup=AMProjeto::codeGroup");
    $j3->setFake();


    $q->setFilter("CMGroupMember::codeUser = $this->codeUser");

    $q->addJoin($j1,"j1");

    $q->addJoin($j3,"j3");

    return $q->execute();
    
  }

  

 
  public function save() {
    global $_conf, $_CMDEVEL;
    $state = $this->state;
    parent::save();

    if($state==self::STATE_NEW) {
      include($_CMDEVEL['path']."/cmvfs.inc.php");
    
      $path = (String) $_conf->app->paths->pages;
      if(empty($path)) {
	Throw new AMException("Cannot save user because the pages dir is not correctly configured. Please, verify your config.xml");
      }
      $path .= "/users/user_".$this->codeUser;

      //if the this doesn't exists, so we can create it, otherwise generate an exception.
      try {
	$dir = new CMvfsLocal($path);
	$this->delete();
	Throw new AMException("You are trying to create a user directory that alredy exists.");
      } catch(CMvfsFileNotFound $e) {
	$dir = new CMvfsLocal($path,0);  //create but not verify if the dir exists
	$dir->register();
      }
    }

  }

  /**
   * Generates a random  password with lowercase letter and numbers.
   *
   * This script has been adapted from http://64.233.167.104/search?q=cache:s_bo4OuRdE0J:melbourne.ug.php.net/content/view/52/76/+php+generate+password&hl=en&client=firefox
   *
   **/
  public function randomPassword($len=4,$mode=self::RPASS_LOWERCASE_NUMBERS_N) {
    $chars=array();
    $chars2=array();
    if ($mode > 1){
      // add numbers to $chars
      for($i=48;$i<=57;$i++) {
	array_push($chars, chr($i));
      }
    }
    if ($mode==3){
      // add uppercase to $chars
      for($i=65;$i<=90;$i++) {
	array_push($chars, chr($i));
      }
    }
    if ($mode > 3){
      // add lowercase to $chars2
      for($i=97;$i<=122;$i++) {
	array_push($chars2, chr($i));
      }
    }else{

      // add lowercase to $chars
      for($i=97;$i<=122;$i++) {
	array_push($chars, chr($i));
      }
    }
    if ($mode==4){
      //build first half of password from $chars2 (lowercase)
      for($i=0;$i<$len;$i++) {
	mt_srand((double)microtime()*1000000);
	$passwd.=$chars2[mt_rand(0,(count($chars2)-1))];
      }
      //build second half of password from $chars (numbers)
      for($i=0;$i<$len;$i++) {
	mt_srand((double)microtime()*1037800);
	$passwd.=$chars[mt_rand(0,(count($chars)-1))];
      }
    }else{
      // build password from $chars
      for($i=0;$i<$len;$i++) {
	mt_srand((double)microtime()*1000000);
	$passwd.=$chars[mt_rand(0,(count($chars)-1))];
      }
    }
    $this->password = $passwd;
  } 



  public function listLastModifiedForums() {

    /**
     This function generates this SQL;
     (
      SELECT Forums.*, count(ForumMessages.code) AS newMessage 
      FROM Forums
      LEFT  JOIN ForumMessages
      ON (Forums.code=ForumMessages.codeForum)
      WHERE 
      ForumMessages.timePost > ALL (SELECT ForumVisits.time 
                                       FROM ForumVisits
 	   			       WHERE ForumVisits.codeUser=77)
      AND
      Forums.code IN (SELECT codeForum
                      FROM ForumMessages
	 	      WHERE codeUser=77) 
      GROUP BY Forums.code
     )
     UNION 
     (
       SELECT *, 0 as newMessage
       FROM Forums
       WHERE code <> ANY (SELECT Forums.code
                          FROM Forums
                          LEFT  JOIN ForumMessages
                          ON (Forums.code=ForumMessages.codeForum)
                          WHERE 
                          ForumMessages.timePost > ALL (SELECT ForumVisits.time 
                                                        FROM ForumVisits
                              	 	   	        WHERE ForumVisits.codeUser=77)
                          AND
                          Forums.code IN (SELECT codeForum
                                          FROM ForumMessages
                                   	  WHERE codeUser=77) 
                          GROUP BY Forums.code
                         ) 
       AND Forums.code  IN (
                            SELECT codeForum
                            FROM ForumMessages
                            WHERE codeUser =77
                           )
     )
    **/

    //This consult was wery hard to imagine, since I'm not a Wizard of SQL.
    // First, it must select all messages that where posted in a Forum that the user visted
    // Second, it must select the forum that the user visited
    // and, last, it must ignore the messages that the user send, because he alredy nows that this message
    // is new.


    $sq1 = new CMQuery('AMForumVisit');
    $sq1->setProjection("AMForumVisit::time");
    $sq1->setFilter("AMForumVisit::codeUser=$this->codeUser AND AMForumVisit::codeForum=AMForum::code");
    
    $sq2 = new CMQuery('AMForumMessage');
    $sq2->setProjection("codeForum");
    $sq2->setFilter("codeUser=$this->codeUser");

    $q1 = new CMQuery('AMForum');
    $q1->setProjection("AMForum::*");
    
    $j = new CMJoin(CMJoin::LEFT);
    $j->setClass('AMForumMessage');
    $j->on("AMForum::code=AMForumMessage::codeForum");
    $j->setFake();
    $q1->addJoin($j,"fake");
    $q1->groupby("AMForum::code");


    $q1->addVariable("newMessages","count(AMForumMessage::code)");

    $q1->setFilter("AMForumMessage::codeUser<>$this->codeUser AND AMForumMessage::timePost > ALL ",$sq1," AND AMForum::code IN ",$sq2);


    $sq3 = new CMQuery('AMForumMessage');
    //    $sq3->addJoin($j,"fake");

    $sq3->setProjection("AMForumMessage::codeForum");
    $sq3->setFilter("AMForumMessage::codeUser<>$this->codeUser AND  AMForumMessage::timePost > ALL ",$sq1," AND AMForum::code IN ",$sq2);

    $q2 = new CMQuery('AMForum');
    $q2->setProjection("AMForum::*");
    $q2->addVariable("newMessages","0");
    $q2->setFilter("AMForum::code <> ALL ",$sq3," AND AMForum::code IN ",$sq2);
    
    $q1->union($q2);
    
    return $q1->execute();
  }

  public function listFriends() {
    unset($_SESSION['amadis']['friends']);
    if(!isset($_SESSION['amadis']['friends'])) {
      
      $q = new CMQuery('AMUser');
      
      $j1 = new CMJoin(CMJoin::INNER);
      $j1->setClass('AMFriend');

      
      //$j2 = new CMJoin(CMJoin::INNER);
      //$j2->setClass('CMEnvSession');
      //$j2->on("EnvSession.codeUser = Friends.codeFriend");
      
      $q->addJoin($j1,"usuarios");
      //$q->addJoin($j2,"sessions");

      
      //$timeOut = CMEnvSession::getTimeOut(time());
      $q->setFilter("Friends.codeUser= $this->codeUser AND Friends.status = '".AMFriend::ENUM_STATUS_ACCEPTED."'");
      //$q->setOrder("EnvSession.timeEnd DESC");
      //$q->groupBy("EnvSession.codeUser");
      
      //$q->setProjection("User.*, EnvSession.*");
      
      $ret = $q->execute();
      
      $_SESSION['amadis']['friends'] = $ret;
      
      $_SESSION['amadis']['friends'] = serialize($ret);
      
    }else $ret = unserialize($_SESSION['amadis']['friends']);
    
    //$_SESSION['amadis']['friends'] = $_SESSION['amadis']['friends'];

    return $ret;
    
  }  
  
  /*
   * Funcao de confirmacao de amigos do usuario. 
   * Implementacao do algoritmo Golum de reconhecimento de amigos
   * dentre os usuarios do ambiente. "AMADIS is my friend"
   */
  public function isMyFriend($codeUser) {
    $friends = $this->listFriends();
    if(!empty($friends->items[$codeUser])) $ret = true;
    else $ret = false;
    serialize($_SESSION['amadis']['friends']);
    return $ret;
  }

  public function listFriendsInvitations() {
    if(empty($_SESSION['last_session'])) {
      $q = new CMQuery('AMFriend');
    
      $j = new CMJoin(CMJoin::LEFT);
      $j->setClass('AMUser');
      $j->on("User.codeUser = Friends.codeUser");
      
      $q->addJoin($j, "invitation");
      $q->setFilter("Friends.codeFriend = $this->codeUser AND Friends.status = '".AMFriend::ENUM_STATUS_NOT_ANSWERED."'");
      
      return $q->execute();

    }else Throw new AMWEFirstLogin;
  }
  
  /**
   * @param int $m Month
   * @param int $y Year
   **/
  public function listDiaryPosts ($m,$y){
    $query=new CMQuery('AMDiarioPost');

    $date_start = mktime(0,0,0,$m,1,$y);

    //the first day of the next month
    if($m==12) {
      $m=0; $y++;
    }
    $date_end = mktime(0,0,0,++$m,1,$y);

    $j2 = new CMJoin(CMJoin::LEFT);
    $j2->on("AMDiarioPost::codePost=AMDiarioComentario::codePost");
    $j2->setFake();
    $j2->setClass('AMDiarioComentario');

    $query->addJoin($j2, "comentarios");

    $query->groupby("AMDiarioPost::codePost");
    $query->addVariable("numComments","count( AMDiarioComentario::codComment )");

    $query->setFilter("AMDiarioPost::codeUser=$this->codeUser AND tempo>=$date_start AND tempo < $date_end");
    $query->setOrder("AMDiarioPost::tempo desc");

    return $query->execute();
  }


  public function listProjects() {
    $q = new CMQuery('AMProjeto');
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass('CMGroup');
    $j->on('AMProjeto::codeGroup=CMGroup::codeGroup');
    $j->setFake();
    
    $j2 = new CMJoin(CMJoin::LEFT);
    $j2->setClass('CMGroupMember');
    $j2->on('CMGroupMember::codeGroup=CMGroup::codeGroup');
    $j2->setFake();
    
    $q->addJoin($j, "grupos");
    $q->addJoin($j2, "membros");
    
    $q->setFilter('CMGroupMember::codeUser = '.$this->codeUser.' AND CMGroupMember::status="'.CMGroupMember::ENUM_STATUS_ACTIVE.'"');
    
    
    return $q->execute();
  }

  public function listMyProjects() {
    if(empty($_SESSION['amadis']['projects'])) {
      $ret = $this->listProjects();
      $_SESSION['amadis']['projects'] = serialize($ret);
    }else {
      $ret = unserialize($_SESSION['amadis']['projects']);
      serialize($_SESSION['amadis']['projects']);
    }

    return $ret;
  }

  public function listProjectsInvitations() {
      $q = new CMQuery('AMProjeto');
      $j = new CMJoin(CMJoin::INNER);
      $j->setClass('CMGroup');
      $j->on('AMProjeto::codeGroup=CMGroup::codeGroup');
      $j->setFake();

      $j2 = new CMJoin(CMJoin::LEFT);
      $j2->setClass('CMGroupMemberJoin');
      $j2->on('CMGroupMemberJoin::codeGroup=CMGroup::codeGroup');

      $q->addJoin($j, "group");
      $q->addJoin($j2, 'invitation');

      $f = 'CMGroupMemberJoin::codeUser = '.$this->codeUser;
      $f.= ' AND CMGroupMemberJoin::status="'.CMGroupMemberJoin::ENUM_STATUS_NOT_ANSWERED.'"';
      $f.= ' AND CMGroupMemberJoin::type="'.CMGroupMemberJoin::ENUM_TYPE_INVITATION.'"';

      $q->setFilter($f);


      return $q->execute();
  }


  public function listProjectsResponses() {
      $q = new CMQuery('AMProjeto');
      $j = new CMJoin(CMJoin::INNER);
      $j->setClass('CMGroup');
      $j->on('AMProjeto::codeGroup=CMGroup::codeGroup');
      $j->setFake();

      $j2 = new CMJoin(CMJoin::LEFT);
      $j2->setClass('CMGroupMemberJoin');
      $j2->on('CMGroupMemberJoin::codeGroup=CMGroup::codeGroup');

      $q->addJoin($j, "group");
      $q->addJoin($j2, 'invitation');

      $f = 'CMGroupMemberJoin::codeUser = '.$this->codeUser;
      $f.= ' AND CMGroupMemberJoin::status<>"'.CMGroupMemberJoin::ENUM_STATUS_NOT_ANSWERED.'"';
      $f.= ' AND CMGroupMemberJoin::ackResponse="'.CMGroupMemberJoin::ENUM_ACKRESPONSE_NOT_ACK.'"';
      $f.= ' AND CMGroupMemberJoin::type="'.CMGroupMemberJoin::ENUM_TYPE_REQUEST.'"';

      $q->setFilter($f);

      return $q->execute();
  }


  /**
   * List the news(noticias) from the projects that the current user participate
   *
   **/
  public function listNewsProjects() {

    $q = new CMQuery('AMProjeto');
      
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass('AMProjectNews');
    $j->on("AMProjectNews::codeProject = AMProjeto::codeProject");
      
    $j1 = new CMJoin(CMjoin::INNER);
    $j1->setClass('CMGroupMember');
    $j1->on("AMProjeto::codeGroup = CMGroupMember::codeGroup");
      
    $q->addJoin($j, "news");
    $q->addJoin($j1, "members");
    
    $q->setFilter("CMGroupMember::codeUser = $this->codeUser");
    $q->setOrder("AMProjectNews::time desc");
    
    return $q->execute();

  }

  /**
   *List the news from the communities that the current user participate
   */
  public function listNewsCommunities() {

    $q = new CMQuery('AMCommunities');
      
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass('AMCommunityNews');
    $j->on("CommunityNews.codeCommunity = Communities.code");
      
    $j1 = new CMJoin(CMJoin::INNER);
    $j1->setClass('AMCommunityMembers');
    $j1->on("CommunityNews.codeCommunity = CommunityMembers.codeCommunity");
      
    $q->addJoin($j, "news");
    $q->addJoin($j1, "members");
      
    $q->setFilter("CommunityMembers.codeUser = $this->codeUser");
    $q->setOrder("CommunityNews.time desc");
    $q->setLimit(0,3);

    return $q->execute();
    
  }

  public function listCommunities() {
    $q = new CMQuery("AMCommunities");
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass("CMGroup");
    $j->on('AMCommunities::codeGroup=CMGroup::codeGroup');
    $j->setFake();
    
    $j2 = new CMJoin(CMJoin::LEFT);
    $j2->setClass("CMGroupMember");
    $j2->on('CMGroupMember::codeGroup=CMGroup::codeGroup');
    $j2->setFake();
    
    $q->addJoin($j, "grupos");
    $q->addJoin($j2, "membros");
    
    $q->setFilter('CMGroupMember::codeUser = '.$this->codeUser.' AND CMGroupMember::status="'.CMGroupMember::ENUM_STATUS_ACTIVE.'"');
 
    return $q->execute();
  }



  /**
   *Lista as minhas comunidades utilizando um cache. Para o usuario atualmente loggado
   */
  public function listMyCommunities() {
    
    if(!empty($_SESSION['amadis']['communities'])) {
      $list = unserialize($_SESSION['amadis']['communities']);
      serialize($_SESSION['amadis']['communities']);
    } else {
      $list = $this->listCommunities();
      $_SESSION['amadis']['communities'] = serialize($list);

    }
    
    return $list;
  }

    public function listCommunitiesInvitations() {
      $q = new CMQuery('AMCommunities');
      $j = new CMJoin(CMJoin::INNER);
      $j->setClass('CMGroup');
      $j->on('AMCommunities::codeGroup=CMGroup::codeGroup');
      $j->setFake();

      $j2 = new CMJoin(CMJoin::LEFT);
      $j2->setClass('CMGroupMemberJoin');
      $j2->on('CMGroupMemberJoin::codeGroup=CMGroup::codeGroup');

      $q->addJoin($j, "group");
      $q->addJoin($j2, 'invitation');

      $f = 'CMGroupMemberJoin::codeUser = '.$this->codeUser;
      $f.= ' AND CMGroupMemberJoin::status="'.CMGroupMemberJoin::ENUM_STATUS_NOT_ANSWERED.'"';
      $f.= ' AND CMGroupMemberJoin::type="'.CMGroupMemberJoin::ENUM_TYPE_INVITATION.'"';

      $q->setFilter($f);
      return $q->execute();
  }

  public function listCommunitiesResponses() {
      $q = new CMQuery('AMCommunities');
      $j = new CMJoin(CMJoin::INNER);
      $j->setClass('CMGroup');
      $j->on('AMCommunities::codeGroup=CMGroup::codeGroup');
      $j->setFake();

      $j2 = new CMJoin(CMJoin::LEFT);
      $j2->setClass('CMGroupMemberJoin');
      $j2->on('CMGroupMemberJoin::codeGroup=CMGroup::codeGroup');

      $q->addJoin($j, "group");
      $q->addJoin($j2, 'invitation');

      $f = 'CMGroupMemberJoin::codeUser = '.$this->codeUser;
      $f.= ' AND CMGroupMemberJoin::status<>"'.CMGroupMemberJoin::ENUM_STATUS_NOT_ANSWERED.'"';
      $f.= ' AND CMGroupMemberJoin::ackResponse="'.CMGroupMemberJoin::ENUM_ACKRESPONSE_NOT_ACK.'"';
      $f.= ' AND CMGroupMemberJoin::type="'.CMGroupMemberJoin::ENUM_TYPE_REQUEST.'"';

      $q->setFilter($f);

      return $q->execute();
  }

  /**
   *Contagem de mensagens no correio
   */
  public function getNumberNotReadMessages() {
    $q = new CMQuery('AMMailMessages');
    
    $q->setFilter("addressee = $this->codeUser and status = '".AMMailMessages::ENUM_STATUS_NOT_READ."'");
    $q->setCount();

    return $q->execute();

  }

  /**
   *Faz contagem de novos comentarios dos meus projetos desde
   *ultimo login.
   */
  public function getLastProjectsComments() {
    if(!empty($_SESSION['last_session'])) {  
      $q = new CMQuery(AMProjeto);
      
      $j = new CMJoin(CMJoin::INNER);
      $j->on("Projects.codeProject = ProjetoComentario.codProjeto");
      $j->setClass('AMProjectComment');
      
      $j2 = new CMJoin(CMJoin::INNER);
      $j2->on("Projects.title = Groups.description");
      $j2->setClass('CMGroup');
      
      $j3 = new CMJoin(CMJoin::INNER);
      $j3->on("GroupMember.codeGroup = Groups.codeGroup");
      $j3->setClass('CMGroupMember');

      $j4 = new CMJoin(CMJoin::INNER);
      $j4->on("Comentarios.codComentario = ProjetoComentario.codComentario");
      $j4->setClass('AMComment');
      
      $q->addJoin($j, "pcomments");
      $q->addJoin($j2, "group");
      $q->addJoin($j3, "members");
      $q->addJoin($j4,"comments");
      
      $q->setFilter("Comentarios.tempo < ".$_SESSION[last_session]->timeEnd." AND GroupMember.codeUser=$this->codeUser");
      
      $q->groupby("Projects.codeProject");
      $q->addVariable("numMessages","count( ProjetoComentario.codComentario)");
      
      return $q->execute();
      

    } else return new CMContainer; // Throw new AMWEFirstLogin;

  }

  public function getLastDiaryComments() {
    if(!empty($_SESSION['last_session'])) {
      $q = new CMQuery('AMDiarioPost');
      
      $j = new CMJoin(CMJoin::INNER);
      $j->on("DiarioPosts.codePost = DiarioComentario.codePost");
      $j->setClass('AMDiarioComentario');
      
      $q->addJoin($j, "comments");
      
      $q->setFilter("DiarioComentario.time > ".$_SESSION['last_session']->timeEnd." AND DiarioPosts.codeUser=$this->codeUser");
      
      $q->groupby("DiarioPosts.codeUser");
      $q->addVariable("numMessages","count( DiarioComentario.codComment)");
      
      return $q->execute();
    } else return new CMContainer;//Throw new AMWEFirstLogin;
  }

  public function getLastMessages() {
    if(!empty($_SESSION['last_session'])) {
      $q = new CMQuery('AMUserMessages');
      
      $q->setFilter("time > ".$_SESSION['last_session']->timeEnd." AND codeUser=$this->codeUser");
      
      $q->groupby("code");
      $q->addVariable("numMessages","count( code)");
      
      return $q->execute();
    
    }else Throw new AMWEFirstLogin;
  }


  public function listMyMessages() {
    $q = new CMQuery('AMUserMessages');
    $q->setFilter('AMUserMessages::codeTo='.$this->codeUser);
    $q->setOrder('AMUserMessages::time DESC');

    $j = new CMJoin(CMJoin::INNER);
    $j->setClass('AMUser');
    $j->using("codeUser");

    $q->addJoin($j,'author');

    return $q->execute();
  }
  
}

?>