<?
/**
 * The users class.
 * This class manage all actions of a user in the environment.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @category AMDBClass
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>, Robson Mendonca <robson@lec.ufrgs.br>
 * @see CMUser, CMEnvinronment
 */

class AMUser extends CMUser 
{

    const RPASS_LOWERCASE=1;
    const RPASS_LOWERCASE_NUMBERS=2;
    const RPASS_LOWERCASE_UPPERCASE_NUMBERS=3;
    const RPASS_LOWERCASE_NUMBERS_N=4;
    const RPASS_NUMBERS=5;

    public function configure() 
    {
        parent::configure();

    //estes sao campos especificos do AMUser que nao estao presentes 
    //no CMUser
        $this->addField("email",CMObj::TYPE_VARCHAR,100,1,0,0);
        $this->addField("address",CMObj::TYPE_VARCHAR,150,1,0,0);
        $this->addField("codeCity",CMObj::TYPE_INTEGER,11,1,0,0);
        $this->addField("cep",CMObj::TYPE_VARCHAR,9,1,0,0);
        $this->addField("aboutMe",CMObj::TYPE_TEXT,65535,1,0,0);
        $this->addField("url",CMObj::TYPE_VARCHAR,150,1,0,0);
        $this->addField("birthDate",CMObj::TYPE_INTEGER,20,1,0,0);

    //in the database, exists an default photo, wich code is 1, so default foto is 1.
        $this->addField("picture",CMObj::TYPE_INTEGER,20,1,1,0);
    }

  /**
   * This function save a new user in AMADIS DB.
   * It too create a new folder to publish your html pages or documents.
   * If the operation fail, throw a AMException and register in AMADIS log system.
   *
   * @access public
   * @param void
   * @return void
   */ 
    public function save() 
    {
        global $_conf, $_CMDEVEL;
        $state = $this->state;
        parent::save();

        if($state==self::STATE_NEW || $state==self::STATE_DIRTY_NEW) {
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

       
    function getUserProjectChats() 
    {

        $q = new CMQuery('AMProject');

        $j1 = new CMJoin(CMJoin::INNER);
        $j1->setClass('AMChatsProject');
        $j1->on("AMProject::codeProject = AMChatsProject::codeProject");
        $j1->setFake();

        $j2 = new CMJoin(CMJoin::INNER);
        $j2->setClass('AMChatRoom');
        $j2->on("AMChatsProject::codeRoom = AMChatRoom::codeRoom");

        $j3 = new CMJoin(CMJoin::INNER);
        $j3->setClass('CMGroupMember');
        $j3->on("CMGroupMember::codeGroup=AMProject::codeGroup");
        $j3->setFake();

        $t = time();
        $q->setFilter("CMGroupMember::codeUser = $this->codeUser AND (AMChatRoom::beginDate>$t OR AMChatRoom::endDate>$t)");

        $q->addJoin($j1,'j1');
        $q->addJoin($j2,"rooms");
        $q->addJoin($j3,'j3');
        return $q->execute();
    }

    function getUserCommunityChats()
    {

        $q = new CMQuery('AMCommunities');

        $j1 = new CMJoin(CMJoin::INNER);
        $j1->setClass('AMChatsCommunities');
        $j1->on("AMCommunities::code = AMChatsCommunities::codeCommunity");
        $j1->setFake();

        $j2 = new CMJoin(CMJoin::INNER);
        $j2->setClass('AMChatRoom');
        $j2->on("AMChatsCommunities::codeRoom = AMChatRoom::codeRoom");

        $j3 = new CMJoin(CMJoin::INNER);
        $j3->setClass('CMGroupMember');
        $j3->on("CMGroupMember::codeGroup=AMCommunities::codeGroup");
        $j3->setFake();

        $t = time();
        $q->setFilter("CMGroupMember::codeUser = $this->codeUser AND (AMChatRoom::beginDate>$t OR AMChatRoom::endDate>$t)");

        $q->addJoin($j1,'j1');
        $q->addJoin($j2,"rooms");
        $q->addJoin($j3,'j3');
        return $q->execute();

    }

  /**
   * Generates a random  password with lowercase letter and numbers.
   *
   * This script has been adapted from http://64.233.167.104/search?q=cache:s_bo4OuRdE0J:melbourne.ug.php.net/content/view/52/76/+php+generate+password&hl=en&client=firefox
   * 
   * @access public
   * @param $len - Length of the string
   * @param $mode - Generation mode
   * @return void
   **/
    public function randomPassword($len=4,$mode=self::RPASS_LOWERCASE_NUMBERS_N) 
    {
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

    public function listForums() 
    {
      
        $q = new CMQuery('AMForum');    
        $q->setFilter("AMForumMessage::codeUser=$this->codeUser");      
        $j = new CMJoin(CMJoin::LEFT);
        $j->setClass('AMForumMessage');
        $j->on("AMForum::code=AMForumMessage::codeForum");
        $j->setFake();
        $q->addJoin($j,"fake");

        $q->addVariable("numMessages","count(AMForumMessage::code)");
        $q->groupBy("AMForum::code");
        return $q->execute();
    }


    public function listLastModifiedForums() 
    {

    /**
     This function generates this SQL;
     
      SELECT Forums.*, count(ForumMessages.code) AS newMessage 
      FROM Forums
      LEFT  JOIN ForumMessages
      ON (Forums.code=ForumMessages.codeForum)
      WHERE 
      ForumMessages.timePost > ALL (SELECT ForumVisits.time 
                                       FROM ForumVisits
 	   			       WHERE ForumVisits.codeUser=$codeUser)
      AND
      Forums.code IN (SELECT codeForum
                      FROM ForumMessages
	 	      WHERE codeUser=$codeUser) 
      GROUP BY Forums.code
     
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

        return $q1->execute();
    }

  /**
   * Add a new friend to this user. If this user is the logged user, clear its friends cache.
   * 
   * @access public
   * @param $codeUser - AMADIS user_id
   * @param $commentary - A short message to new friend =)
   * @return void
   */
    public function addFriend($codeUser, $commentary) 
    {

        $friend = new AMFriend;
        $friend->codeFriend = $codeUser;
        $friend->codeUser = $this->codeUser;
        $friend->comentary = $commentary;
        $friend->status = AMFriend::ENUM_STATUS_ACCEPTED;
        $friend->time = time();

        try {
            $friend->save();

            $f = new AMFriend;
            $f->codeFriend = $this->codeUser;
            $f->codeUser = $codeUser;
            $f->comentary = $comentary;
            $f->status = AMFriend::ENUM_STATUS_NOT_ANSWERED;
            $f->time = time();
            try {
                $f->save();
            }catch(CMException $e) {
	//tratar excessao
            }
        }catch(CMException $e) {
      //tratar excessao
        }
        if($_SESSION['user']->codeUser == $this->codeUser) {
            unset($_SESSION['amadis']['friends']);
        }
    }


    public function listFriends() 
    {
        if($_SESSION['user']->codeUser != $this->codeUser) {
            $q = new CMQuery('AMUser');

            $j1 = new CMJoin(CMJoin::INNER);
            $j1->setClass('AMFriend');
            $j1->on("AMUser::codeUser = AMFriend::codeFriend");

            $q->addJoin($j1,"usuarios");

            $q->setFilter("Friends.codeUser= $this->codeUser AND Friends.status = '".AMFriend::ENUM_STATUS_ACCEPTED."'");

            return $q->execute();

        }
        if(!isset($_SESSION['amadis']['friends'])) {
            
            $q = new CMQuery('AMUser');

            $j1 = new CMJoin(CMJoin::INNER);
            $j1->setClass('AMFriend');
            $j1->on("AMUser::codeUser = AMFriend::codeFriend");


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

            $_SESSION['amadis']['friends'] = serialize($ret);

        }else {
            $ret = unserialize($_SESSION['amadis']['friends']);
        }

        return $ret;

    }
    
  /**
   * Confirmation function to user friends
   * Golum algorithm implementation of the friends recognise
   * among environment users. "AMADIS is my friend? It's like us?"
   *
   * @access public
   * @param int $codeUser - AMADIS user_id
   * @return Boolean - Confirmation of the test =P 
   */
    public function isMyFriend($codeUser) 
    {
        $friends = $this->listFriends();
        if(!empty($friends->items[$codeUser])) $ret = true;
        else $ret = false;
        serialize($_SESSION['amadis']['friends']);
        return $ret;
    }

  /**
   * List of invitation maked for AMADIS user that become you frind =)
   *
   * @access public
   * @param void
   * @return CMContainer - List of the invitations
   */
    public function listFriendsInvitations() 
    {
        if(empty($_SESSION['last_session'])) {
            $q = new CMQuery('AMUser');

            $j = new CMJoin(CMJoin::LEFT);
            $j->setClass('AMFriend');
            $j->on("User.codeUser = Friends.codeFriend");

            $q->addJoin($j, "invitation");
            $q->setFilter("Friends.codeUser = $this->codeUser AND Friends.status = '".AMFriend::ENUM_STATUS_NOT_ANSWERED."'");

            return $q->execute();

        }else Throw new AMWEFirstLogin;
    }
    
  /**
   * List post for a diary
   *
   * @access public
   * @param int $m - Month
   * @param int $y - Year
   * @return CMContainer - List of post from a diary
   **/
    public function listBlogPosts ($m,$y)
    {
        $query=new CMQuery('AMBlogPost');

        $date_start = mktime(0,0,0,$m,1,$y);

    //the first day of the next month
        if($m==12) {
            $m=0; $y++;
        }
        $date_end = mktime(0,0,0,++$m,1,$y);

        $j2 = new CMJoin(CMJoin::LEFT);
        $j2->on("AMBlogPost::codePost=AMBlogComment::codePost");
        $j2->setFake();
        $j2->setClass('AMBlogComment');

        $query->addJoin($j2, "comentarios");

        $query->groupby("AMBlogPost::codePost");
        $query->addVariable("numComments","count( AMBlogComment::codeComment )");

        $query->setFilter("AMBlogPost::codeUser=$this->codeUser AND time>=$date_start AND tempo < $date_end");
        $query->setOrder("AMBlogPost::tempo desc");
        

        return $query->execute();
    }

    
    public function listLastBlogPosts() 
    {
        $query=new CMQuery('AMBlogPost');
        $j2 = new CMJoin(CMJoin::LEFT);
        $j2->on("AMBlogPost::codePost=AMBlogComment::codePost");
        $j2->setFake();
        $j2->setClass('AMBlogComment');
        $query->groupby("AMBlogPost::codePost");
        $query->addVariable("numComments","count( AMBlogComment::codeComment )");

        
        $query->setOrder("AMBlogPost::tempo desc");
        $query->setLimit(0,20);
        $query->setFilter("AMBlogPost::codeUser=$this->codeUser");
        
        return $query->execute();
    }

  /**
   * List project of the a user
   *
   * @access public
   * @param void
   * @return CMContainer - List of the projects from a diary
   **/
    public function listProjects() 
    {
        $q = new CMQuery('AMProject');
        $j = new CMJoin(CMJoin::INNER);
        $j->setClass('CMGroup');
        $j->on('AMProject::codeGroup=CMGroup::codeGroup');
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

    public function listMyProjects() 
    {
        if(empty($_SESSION['amadis']['projects'])) {
            $ret = $this->listProjects();
            $_SESSION['amadis']['projects'] = serialize($ret);
        }else {
            $ret = unserialize($_SESSION['amadis']['projects']);
            serialize($_SESSION['amadis']['projects']);
        }

        return $ret;
    }

    public function listProjectsInvitations() 
    {
        $q = new CMQuery('AMProject');
        $j = new CMJoin(CMJoin::INNER);
        $j->setClass('CMGroup');
        $j->on('AMProject::codeGroup=CMGroup::codeGroup');
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

    public function listProjectsResponses() 
    {
        $q = new CMQuery('AMProject');
        $j = new CMJoin(CMJoin::INNER);
        $j->setClass('CMGroup');
        $j->on('AMProject::codeGroup=CMGroup::codeGroup');
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
    public function listNewsProjects() 
    {
        $q = new CMQuery('AMProject');

        $j = new CMJoin(CMJoin::INNER);
        $j->setClass('AMProjectNews');
        $j->on("AMProjectNews::codeProject = AMProject::codeProject");

        $j1 = new CMJoin(CMjoin::INNER);
        $j1->setClass('CMGroupMember');
        $j1->on("AMProject::codeGroup = CMGroupMember::codeGroup");

        $q->addJoin($j, "news");
        $q->addJoin($j1, "members");

        $q->setFilter("CMGroupMember::codeUser = $this->codeUser");
        $q->setOrder("AMProjectNews::time desc");

        return $q->execute();

    }

  /**
   * List the news from the communities that the current user participate
   */
    public function listNewsCommunities() 
    {

        $q = new CMQuery('AMCommunities');

        $j = new CMJoin(CMJoin::INNER);
        $j->setClass('AMCommunityNews');
        $j->on('AMCommunityNews::codeCommunity = AMCommunities::code');

        $j1= new CMJoin(CMJoin::INNER);
        $j1->setClass('CMGroup');
        $j1->on('CMGroup::codeGroup=AMCommunities::codeGroup');
        $j1->setFake();

        $j2 = new CMJoin(CMJoin::INNER);
        $j2->setClass('CMGroupMember');
        $j2->on('CMGroup::codeGroup=CMGroupMember::codeGroup');
        $j2->setFake();



        $q->addJoin($j, "news");
        $q->addJoin($j1);
        $q->addJoin($j2);

        $q->setFilter("CMGroupMember::codeUser = $this->codeUser");
        $q->setOrder("AMCommunityNews::time desc");
        $q->setLimit(0,3);


        return $q->execute();

    }

    public function listCommunities() 
    {
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
    public function listMyCommunities() 
    {
        
        if(!empty($_SESSION['amadis']['communities'])) {
            $list = unserialize($_SESSION['amadis']['communities']);
            serialize($_SESSION['amadis']['communities']);
        } else {
            $list = $this->listCommunities();
            $_SESSION['amadis']['communities'] = serialize($list);

        }
        
        return $list;
    }

    public function listCommunitiesInvitations() 
    {
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

    public function listCommunitiesResponses() 
    {
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
    public function getLastProjectsComments() 
    {
        $q = new CMQuery('AMProject');

        $j = new CMJoin(CMJoin::INNER);
        $j->on("AMProject::codeProject = AMProjectComment::codProjeto");
        $j->setClass('AMProjectComment');

        $j2 = new CMJoin(CMJoin::INNER);
        $j2->on("AMProject::codeGroup = CMGroup::codeGroup");
        $j2->setClass('CMGroup');

        $j3 = new CMJoin(CMJoin::INNER);
        $j3->on("CMGroupMember::codeGroup = CMGroup::codeGroup");
        $j3->setClass('CMGroupMember');

        $j4 = new CMJoin(CMJoin::INNER);
        $j4->on("AMComment::codComentario = AMProjectComment::codComentario");
        $j4->setClass('AMComment');

        $q->addJoin($j, "pcomments");
        $q->addJoin($j2, "group");
        $q->addJoin($j3, "members");
        $q->addJoin($j4,"comments");

        $q->setFilter("AMComment::tempo > ".$_SESSION['last_session']->timeEnd." AND CMGroupMember::codeUser=$this->codeUser");

        $q->groupby("AMProject::codeProject");
        $q->addVariable("numMessages","count( AMProjectComment::codComentario)");

        return $q->execute();
    }

    public function getLastBlogComments() 
    {
        $q = new CMQuery('AMBlogPost');

        $j = new CMJoin(CMJoin::INNER);
        $j->on("AMBlogPost::codePost = AMBlogComment::codePost");
        $j->setClass('AMBlogComment');

        $q->addJoin($j, "comments");

        $q->setFilter("AMBlogComment::time > ".$_SESSION['last_session']->timeEnd." AND AMBlogPost::codeUser=$this->codeUser");

        $q->groupby("AMBlogPost::codeUser");
        $q->addVariable("numMessages","count( AMBlogComment::codComment)");

        return $q->execute();
    }

    public function getLastMessages() 
    {
        if(!empty($_SESSION['last_session'])) {
            $q = new CMQuery('AMUserMessages');

            $q->setFilter("time > ".$_SESSION['last_session']->timeEnd." AND codeUser=$this->codeUser");

            $q->groupby("code");
            $q->addVariable("numMessages","count( code)");

            return $q->execute();

        }else Throw new AMWEFirstLogin;
    }


  /**
   * List public messages(scraps)
   *
   * @access public
   * @param int $ini - Initial row of DB_CURSOR
   * @param int $lenght - Number of rows will return
   * @return Array CMContainer and number of AFFECTED_ROWS
   */
    public function listMyMessages($ini=0, $lenght=10) 
    {
        $q = new CMQuery('AMUserMessages');

        $filter = 'AMUserMessages::codeTo='.$this->codeUser;
        $order = 'AMUserMessages::time DESC';

        $q->setFilter($filter);
        $q->setOrder($order);

        $j = new CMJoin(CMJoin::INNER);
        $j->setClass('AMUser');
        $j->using("codeUser");

        $q->addJoin($j,'author');

        $q->setCount();
        $count = $q->execute();

        $q = new CMQuery('AMUserMessages');
        $q->setFilter($filter);
        $q->setOrder($order);
        $q->addJoin($j, 'author');

        $q->setLimit($ini, $lenght);

        return array('count'=>$count, $q->execute());
    }
    
}

