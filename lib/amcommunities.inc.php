<?php
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

class AMCommunities extends CMObj implements  CMACLAppInterface
{

    const ENUM_FLAGAUTH_ALLOW = "ALLOW";
    const ENUM_FLAGAUTH_REQUEST = "REQUEST";
    const ENUM_STATUS_NOT_AUTHORIZED = "NOT_AUTHORIZED";
    const ENUM_STATUS_AUTHORIZED = "AUTHORIZED";

	//privileges to access this community
    const PRIV_ADMIN = "admin";
    const PRIV_ADD_USERS = "add_users";
    const PRIV_ADD_PROJECTS = "add_projects";
    const PRIV_MANAGE_COMMUNICATION = "manage_communication";

    protected $cacheACO;

    public function configure()
    {
        $this->setTable("Communities");

        $this->addField("code",CMObj::TYPE_INTEGER,"20",1,0,0);
        $this->addField("description",CMObj::TYPE_VARCHAR,"255",1,0,0);
        $this->addField("name",CMObj::TYPE_VARCHAR,"30",1,0,0);
        $this->addField("codeGroup",CMObj::TYPE_INTEGER,"20",1,0,0);
        $this->addField("codeACO",CMObj::TYPE_INTEGER,"20",1,0,0);
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

   /** 
	* Create related objects before save.
	*
	* @see CMObj
	* @todo Create a wrapper to handle the change of status an sync with group.
	**/
    public function save()
    {

    //if is the first time that the object is saved, and it has some data
    // inside it, create a new group.
        if($this->state==self::STATE_DIRTY_NEW) {
      //create a new group for the project
            $group = new CMGroup;
            $group->description = "Community ".$this->name;

            if($this->status==self::ENUM_FLAGAUTH_ALLOW) {
                $group->managed = CMGroup::ENUM_MANAGED_NOT_MANAGED;
            } else {
                $group->managed = CMGroup::ENUM_MANAGED_MANAGED;
            }

            $group->time = time();

            try {
                $group->save();
            } catch(CMDBException $e) {
                Throw new AMException("An error ocurred creating the project group.");
            }
            $this->codeGroup = $group->codeGroup;

            //is the default behavior of the object
            //to create a new member of the community
            //with the logged user (that is the user
            //that are creating the community) as its
            //member
            $member = new CMGroupMember;
            $member->codeGroup = (integer) $this->codeGroup;
            $member->codeUser =  (integer) $_SESSION['user']->codeUser;
            $member->time = time();
            try {
                $member->save();
            } catch(CMDBException $e) {
                Throw new AMException("An error ocurred creating the project group.");
            }

	        //Creates an ACL and gives the current user the admin
	        //privilege

            $aco = new CMACO($this);
            $aco->description = "Forum ".$this->name;
            $aco->time = time();
            try {
                $aco->save();
            } catch(CMDBException $e) {
                Throw new AMException("An error ocurred creating the project group.");
            }
            $this->codeACO = (integer) $aco->code;
            $aco->addUserPrivilege((integer) $_SESSION['user']->codeUser,
            self::PRIV_ADMIN);

        }
         
        parent::save();
    }


    /** 
     * List the project that are associated with this community.
     * */
    public function listProjects($ini=0, $lenght=5)
    {
        $q = new CMQuery(AMProject);

        $j = new CMJoin(CMJoin::INNER);
        $j->setClass(AMCommunityProjects);
        $j->on("AMProject::codeProject = AMCommunityProjects::codeProject");

        $q->addJoin($j, "projects");
        $q->setLimit($ini, $lenght);
        $q->setFilter("AMCommunityProjects::codeCommunity = $this->code");
        return $q->execute();
    }

    public function listNews($ini=0, $lenght=5)
    {
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

    
    public function listForums()
    {
        $q = new CMQuery('AMForum');

        $j = new CMJoin(CMJoin::INNER);
        $j->setClass('AMCommunityForum');
        $j->on("AMForum::code = AMCommunityForum::codeForum");

        $j2 = new CMJoin(CMJoin::LEFT);
        $j2->on("AMForum::code=AMForumMessage::codeForum");
        $j2->setClass('AMForumMessage');

        $q->addJoin($j, "community");
        $q->addJoin($j2, "messages");
        $q->setFilter("codeCommunity=$this->code");

        $q->groupby("AMForum::code");
        $q->addVariable("numMessages","count( AMForumMessage::code )");
        $q->addVariable("lastMessageTime","max( AMForumMessage::timePost )");

        return $q->execute();
    }



    public function getOpenRooms()
    {
        $q = new CMQuery('AMChatRoom');

        $j = new CMJoin(CMJoin::NATURAL);
        $j->setClass('AMChatsCommunities');

        $q->addJoin($j, "room");

        $time = time();
        $q->setFilter("chatType='".AMChatRoom::ENUM_CHAT_TYPE_COMMUNITY."' AND codeCommunity = ".$this->code." AND beginDate <= $time AND endDate > $time");

        return $q->execute();

    }

    public function getMarkedChats()
    {
        
        $q = new CMQuery('AMChatRoom');

        $j = new CMJoin(CMJoin::NATURAL);
        $j->setClass('AMChatsCommunities');
        $j->fake = true;

        $j1 = new CMJoin(CMJoin::INNER);
        $j1->setClass('AMUser');
        $j1->on("AMUser::codeUser = AMChatRoom::codeUser");

        $q->addJoin($j1, "user");
        $q->addJoin($j, "aux");

        $q->setProjection("ChatRoom.*, User.name");
        $q->groupBy("AMChatRoom::codeRoom");
        $q->setFilter("beginDate > ".time()." AND codeCommunity = $this->code");

        return $q->execute();


    }

    /*********************
    * Group managment
    ********************/

    /**
     * Return the associated group of this community.
     *  
     * @return CMGroup The group associated with this object.
     **/
    public function getGroup()
    {
        if(empty($_SESSION[AMADIS][Community][$this->code][group])) {
            $g = new CMGroup;
            $g->codeGroup = $this->codeGroup;
            $g->load();
            $_SESSION[AMADIS][Community][$this->code][group] = $g;
        }  else {
            $g = $_SESSION[AMADIS][Community][$this->code][group];
        }
        return $g;
    }



    /**
     * Get the ACO of this Community.
     *
     * Return the respective aco of this community. This
     * function uses a cache to avoid unnecessary querys
     * to the database.
     *
     * @return CMAco A CMAco object.
     **/
    public function getACO()
    {
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

    /**
     * List the privileges of this community.
     *
     * @return array An array contaning the priviles valid in this class.
     **/
    public function listPrivileges()
    {
        return  array(self::PRIV_ADMIN,
        self::PRIV_ADD_USERS,
        self::PRIV_ADD_PROJECTS,
        self::PRIV_MANAGE_COMMUNICATION);
    }

	/**
	 * List the names of the privilges.
 	 *
	 * This function return an array with the privilege as key and
	 * with an string as value. This string represents an user
	 * readable message that represents the privilege in the
	 * current language.
	 *
 	 * @see CMi18n
	 * @return array An array contaning the priviles valid in this class.
	 **/
    public function listPrivilegesMessages()
    {
        $_lang = $_CMAPP[i18n]->getTranslationArray("communities");
        return array( self::PRIV_ADMIN=>$_lang['privs_admin'],
        self::PRIV_ADD_USERS=>$_lang['privs_add_user'],
        self::PRIV_ADD_PROJECTS=>$_lang['privs_add_projects'],
        self::PRIV_MANAGE_COMMUNICATION=>$_lang['privs_manage_communication']);
    }


}