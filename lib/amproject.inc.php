<?php

class AMProject extends CMObj {
    protected $imagens;


    public function configure() {

        $this->setTable("Projects");

        $this->addField("codeProject",CMObj::TYPE_INTEGER,11,1,0,1);
        $this->addField("title",CMObj::TYPE_VARCHAR,100,1,0,0);
        $this->addField("description",CMObj::TYPE_TEXT,65535,1,0,0);
        $this->addField("status",CMObj::TYPE_INTEGER,4,1,0,0);
        $this->addField("hits",CMObj::TYPE_INTEGER,20,1,0,0);
        $this->addField("codeGroup",CMObj::TYPE_INTEGER,20,1,0,0);
        $this->addField("time",CMObj::TYPE_INTEGER,11,1,0,0);

     //in the database, exists an default image, wich code is 2, so default value is 2.
        $this->addField("image",CMObj::TYPE_INTEGER,20,1,0,0);

        $this->addPrimaryKey("codeProject");

    }


    public function save() {
        global $_conf, $_CMDEVEL;

        unset($_SESSION['amadis']['projects']);
        $state = $this->state;

        if(($state==self::STATE_NEW) || ($state==self::STATE_DIRTY_NEW) ) {
      //create a new group for the project
            $group = new CMGroup;
            $group->description = "Project ".$this->title;
            $group->managed = CMGroup::ENUM_MANAGED_MANAGED;
            $group->time = time();
            try {
                $group->save();
            } catch(CMDBException $e) {            	
                Throw new AMException("An error ocurred creating the project group.");
            }
            $this->codeGroup = $group->codeGroup;
        }

        parent::save();
        unset($_SESSION['amadis']['projects']);


        if(($state==self::STATE_NEW) || ($state==self::STATE_DIRTY_NEW)) {
      //create an new disk space for the project
            include("cmvfs.inc.php");

            $path = (String) $_conf->app->paths->pages;
            if(empty($path)) {
                Throw new AMException("Cannot save project because the pages dir is not correctly configured. Please, verify your config.xml");
            }
            $path.= "/projects/project_".$this->codeProject;

      //if the this doesn't exists, so we can create it, otherwise generate an exception.
            try {
                $dir = new CMvfsLocal($path);
                $this->delete();
                Throw new AMException("You are trying to create a project directory that alredy exists.");
            }
            catch(CMvfsFileNotFound $e) {
                $dir = new CMvfsLocal($path,0) ;  //create but not verify if the dir exists
                $dir->register();
            }
        }

    }

    
    public function getGroup() {
        if(empty($_SESSION['AMADIS']['Project'][$this->codeProject]['group'])) {
            $g = new CMGroup;
            $g->codeGroup = $this->codeGroup;
            $g->load();
            $_SESSION['AMADIS']['Project'][$this->codeProject]['group'] = $g;
        }  else {
            $g = $_SESSION['AMADIS']['Project'][$this->codeProject]['group'];
        }
        return $g;
    }

    public function getStatus(){
        $q = new CMQuery('AMProjectStatus');
        $q->setFilter("code = ".$this->status);
        $res = $q->execute();
        $res = array_pop($res->items);
        return $res->name;

    }

    static function listAvaiableStatus() {
        $q = new CMQuery('AMProjectStatus');
        $res = $q->execute();
        return $res;
    }




    public function listNews($ini=0, $lenght=4) {
        $q = new CMQuery('AMProjectNews');

        $j= new CMJoin(CMJoin::INNER);
        $j->setClass('AMUser');
        $j->using('codeUser');

        $q->addJoin($j,'autor');

        $q->setFilter('AMProjectNews::codeProject = '.$this->codeProject);
        $q->setCount();
        $count = $q->execute();

        $q = new CMQuery('AMProjectNews');

        $q->addJoin($j,'autor');
        $q->setFilter('AMProjectNews::codeProject = '.$this->codeProject);
        $q->setLimit($ini,$lenght);
        $q->setOrder("AMProjectNews::time desc");

        return array("count"=>$count, $q->execute());

    }


    public function listComments($ini=0, $lenght=3, $time="") {
        $q = new CMQuery('AMComment');

        $j1 = new CMJoin(CMJoin::INNER);
        $j1->setClass('AMProjectComment');
        $j1->on('AMProjectComment::codeComment = AMComment::codeComment');

        $j2 = new CMJoin(CMJoin::INNER);
        $j2->setClass('AMUser');
        $j2->on("AMComment::codeUser = AMUser::codeUser");

        $q->addJoin($j1,"comentarios");
        $q->addJoin($j2,"usuarios");

        $q->setCount();

        if(!empty($time) && !empty($_SESSION['last_session'])) {
            $q->setFilter("AMProjectComment::codeProject=$this->codeProject AND time > ".$_SESSION['last_session']->timeEnd);
            $q->setOrder("time desc");
        }else {
            $q->setFilter("AMProjectComment::codeProject=".$this->codeProject);
            $q->setLimit($ini, $lenght);
        }
        
        $count = $q->execute();

        $q = new CMQuery('AMComment');

        $q->addJoin($j1,"comentarios");
        $q->addJoin($j2,"usuarios");

        if(!empty($time) && !empty($_SESSION['last_session'])) {
            $q->setFilter("AMProjectComment::codeProject=$this->codeProject AND time < ".$_SESSION['last_session']->timeEnd);
            $q->setOrder("time desc");
        }else {
            $q->setFilter("AMProjectComment::codeProject=".$this->codeProject);
            $q->setLimit($ini, $lenght);
        }
		
        return array("count"=>$count, $q->execute());

    }



    public function hit() {
        if(!empty($_CMAPP['amadis']['projects']['hist'][$this->codeProject])) return true;

        $_CMAPP['amadis']['projects']['hist'][$this->codeProject] = true;
        $this->hits++;
        $this->save();
    }


    public function listAreas() {
        $q = new CMQuery('AMArea');
        $j = new CMJoin(CMJoin::INNER);
        $j->setClass('AMProjectArea');
        $q->addJoin($j,"areas");

        $q->setLimit(4,0);
        $q->setFilter("AMProjectArea::codeProject=".$this->codeProject." AND AMProjectArea::codeArea = AMArea::codeArea");

        return $q->execute();
    }


    public function listForums() {
        $q = new CMQuery('AMForum');

        $j = new CMJoin(CMJoin::INNER);
        $j->setClass('AMProjectForums');
        $j->on("AMForum::code = AMProjectForums::codeForum");

        $j2 = new CMJoin(CMJoin::LEFT);
        $j2->on("AMForum::code=AMForumMessage::codeForum");
        $j2->setClass('AMForumMessage');

        $q->addJoin($j, "projeto");
        $q->addJoin($j2, "messages");
        $q->setFilter("codeProject=$this->codeProject");

        $q->groupby("AMForum::code");
        $q->addVariable("numMessages","count( AMForumMessage::code )");
        $q->addVariable("lastMessageTime","max( AMForumMessage::timePost )");

        return $q->execute();
    }
    
    public function getLibrary(){
        $projlib = new AMProjectLibraryEntry($this->codeProject);
        try{
            $projlib->load();
        }catch(CMException $e){
            new AMErrorReport($e, 'AMProjectLibraryEntry');
        }
        return $projlib->codeLibrary;
    }
    
    public function getOpenRooms() {
        $q = new CMQuery('AMChatRoom');

        $j = new CMJoin(CMJoin::NATURAL);
        $j->setClass('AMChatsProject');

        $q->addJoin($j, "room");

        $time = time();
        $q->setFilter("chatType='".AMChatRoom::ENUM_CHAT_TYPE_PROJECT."' AND codeProject = ".$this->codeProject." AND beginDate <= $time AND endDate > $time");

        return $q->execute();
  
    }

    public function getMarkedChats() {
        
        $q = new CMQuery('AMChatRoom');

        $j = new CMJoin(CMJoin::NATURAL);
        $j->setClass('AMChatsProject');
        $j->fake = true;

        $j1 = new CMJoin(CMJoin::INNER);
        $j1->setClass('AMUser');
        $j1->on("AMUser::codeUser = AMChatRoom::codeUser");

        $q->addJoin($j1, "user");
        $q->addJoin($j, "aux");

        $q->setProjection("ChatRoom.*, User.name");
        $q->groupBy("AMChatRoom::codeRoom");
        $q->setFilter("beginDate > ".time()." AND codeProject = $this->codeProject");

        return $q->execute();


    }

}