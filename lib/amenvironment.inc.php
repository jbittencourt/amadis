<?php
/**
 * The AMEnvironment class represents the basic environment in witch the application is running.
 *
 * The AMEnviroment class models the state of the running application. In 
 * this class, the system can make actions that modify the context of the running
 * applications, such as login, basic statistics, user management, etc.
 *
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    AMADIS
 * @subpackage Core
 * @version    1.2
 * @since      File available since Release 1.2.0
 * @author     Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see 	   config.inc.php
 **/
class AMEnvironment extends CMEnvironment
{

    
    static public function processActionRequest() {
        $action = "";
        if(array_key_exists('action', $_REQUEST)) {
            $action = $_REQUEST['action'];
        }
        return $action;
    }
    
    
    /**
     * Returns an array containing basic statistics for the environment.
     *
     * @return array Basic statistics of the environment.
     */
    public function getStats()
    {
        $q1 = new CMQuery('AMProject');
        $q1->setCount();

        $q2 = new CMQuery('AMUser');
        $q2->setCount();

        $q3 = new CMQuery('AMCommunities');
        $q3->setCount();

        $resul = array();
        $resul['projects'] = $q1->execute();
        $resul['communities'] = $q3->execute();
        $resul['people'] = $q2->execute();
        $resul['courses'] = 0;

        return $resul;
    }
    
    public function listAreas()
    {
        $q = new CMQuery('AMArea');
        $q->setOrder("AMArea::name asc");
        return $q->execute();
    }


    public function listProjectsByArea($codArea, $ini=0, $length=5)
    {
        $q= new CMQuery('AMProject');
        $j = new CMJoin(CMJoin::INNER);
        $j->setClass('AMProjectArea');
        $j->on("AMProject::codeProject = AMProjectArea::codeProject");

        $result = array();

        $q->addJoin($j, "area");
        $q->setOrder("time desc");
        $q->setFilter("codeArea =". $codArea);
        $q->setCount();
        $result['count'] = $q->execute();

        $q = new CMQuery('AMProject');
        $j1 = new CMJoin(CMJoin::INNER);
        $j1->setClass('AMArea');
        $j1->on("AMArea::codeArea = AMProjectArea::codeArea");

        $q->addJoin($j, "areas");
        $q->addJoin($j1, "area");
        $q->setOrder("time desc");
        $q->setFilter("AMArea::codArea =". $codArea);
        $q->setLimit($ini, $length);
        $result[] = $q->execute();
        return $result;
    }

    public function searchProjects($searchString, $ini=0, $length=10)
    {
        $s = new CMSearch('AMProject');
        $s->addSearchFields("AMProject::title","AMProject::description");
        $s->setSeachString($searchString);

        $q = clone $s;
        $q->setCount();
        $count = $q->execute();

        if(!empty($final)) {
            $s->setLimit($init, $final);
        }
        
        return array("count"=>$count, $s->execute());
    }

    public function listAllProjects($ini=0, $length=5)
    {
        $q = new CMQuery('AMProject');
        $result = array();
        $q->setCount();
        $result['count'] = $q->execute();

        $q = new CMQuery('AMProject');
        $q->setOrder("title asc");
        $q->setLimit($ini, $length);
        $result[] = $q->execute();
        return $result;
    }


    public function listTopProjects($limit=5)
    {
        $q = new CMQuery('AMProject');
        $q->setOrder("hits desc");
        $q->setLimit(0, $limit);
        return $q->execute();
    }


    public function listNewProjects($limit=3)
    {
        $q = new CMQuery('AMProject');
        $q->setOrder("time desc");
        $q->setLimit(0, $limit);
        return $q->execute();
    }


    function listCities()
    {   
        $q = new CMQuery('AMCity');
        $j = new CMJoin(CMJoin::INNER);
        $j->setClass('AMState');

        $q->addJoin($j, "state");

        $q->setOrder("States.name asc");
        $q->setDistinct();

        return $q->execute();
    }

    public function listWarnings()
    {
        $q = new CMQuery('AMWarning');
        return $q->execute();
    }

    public function listActiveWarnings()
    {
        $time = time();
        $q = new CMQuery('AMWarning');
        $q->setFilter("AMWarning::timeStart > $time AND AMWarning::timeEnd < $time");
        return $q->execute();
    }
    

  /**
   * Listagem e busca de usuarios.
   */
    public function countUsers()
    {
        $q = new CMQuery('AMUser');
        $q->setCount();
        return $q->execute();
    }

    public function countSearchResult($q)
    {
        $q->setCount();
        return $q->execute();
    }
    
    public function listUsers()
    {
        
        $args = func_get_args();
        $ini = $args[0];
        $final = $args[1];
        $filter = $args[2];
        $listUsers = new CMQuery('AMUser');
        $listUsers->setFilter($filter);
        $listUsers->setOrder("codeUser asc");
        $listUsers->setLimit($ini, $final);
        return $listUsers->execute();
    }

    public function searchUsers($searchString, $init="", $final="")
    {
        $s = new CMSearch('AMUser');
        $s->addSearchFields("AMUser::name","AMUser::username");
        $s->setSeachString($searchString);

        $q = clone $s;
        $q->setCount();
        $count = $q->execute();

        if(!empty($final)) {
            $s->setLimit($init, $final);
        }
        
        return array("count"=>$count, $s->execute());
    }
    
   /**
    *Lista as ultimas postagens nos diarios
    *de usuarios.
    */
    public function listLastDiaryPosts()
    {
        $q = new CMQuery('AMBlogPost');

        $j2 = new CMJoin(CMJoin::INNER);
        $j2->setClass('AMUser');
        $j2->on("AMBlogPost::codeUser = AMUser::codeUser");

        $q->addJoin($j2, "author");

        $q->setLimit(0,5);
        $q->setOrder("AMBlogPost::time desc");
        return $q->execute();

    }

  /**
   * List the diary profiles of the users that have alredy blogged, ordered by the last post message
   **/
    public function listDiaries($lower_limit=0,$upper_limit=0)
    {
        
        $q = new CMQuery('AMUser');

        $j1 = new CMJoin(CMJoin::LEFT);
        $j1->setClass('AMBlogProfile');
        $j1->on("AMBlogProfile::codeUser=AMUser::codeUser");

        $j2 = new CMJoin(CMJoin::INNER);
        $j2->setClass('AMBlogPost');
        $j2->on("AMBlogPost::codeUser=AMUser::codeUser");
        $j2->setFake();

        $q->addJoin($j1,"profile");
        $q->addJoin($j2,"");


    //count the total number of rows for paggination
        $c = clone $q;
        $c->setCount();
        $count = $c->execute();

    //confirure the query with extra params
        $q->addVariable("lastPostTime","MAX(AMBlogPost::time)");
        $q->groupBy("AMUser::codeUser");
        $q->setOrder("AMBlogPost::time desc");

        if(!empty($upper_limit)) {
            $q->setLimit($lower_limit,$upper_limit);
        }

        return array("count"=>$count,"data"=>$q->execute());
    }

  /**
   * Lista 5 ultimos usuarios logados no exato momento
   */
    public function listLastUsersLogeds($numRows=5)
    {
        $q = new CMQuery('AMUser');

        $j = new CMJoin(CMJoin::NATURAL);
        $j->setClass('CMEnvSession');

        $q->addJoin($j, "sessions");
        $q->setLimit(0,$numRows);
        $q->setOrder("timeEnd DESC");
        $timeOut = CMEnvSession::getTimeout(time());
        $filter = "timeEnd > $timeOut AND visibility = '".CMEnvSession::ENUM_VISIBILITY_VISIBLE."'";
        if(!empty($_SESSION['user'])) {
            $filter.= "AND User.codeUser != ".$_SESSION['user']->codeUser;
        }
        $q->setFilter($filter);
        $q->groupBy("User.codeUser");

        return $q->execute();

    }

  /**
   *Lista as 5 maiores comunidades do AMADIS
   *SELECT COUNT( * ) FROM CommunityMembers INNER JOIN Communities ON
   *( CommunityMembers.codeCommunity = Communities.code ) GROUP BY codeCommunity
   */
    public function listBiggerCommunities($limit=5)
    {
    //$q = new CMQuery('AMCommunities');    
    //     $j = new CMJoin(CMJoin::LEFT);
    //     $j->setClass('AMGroupMembers');
    //     $j->on("AMCommunities::codeGroup = AMGroupMembers::codeCommunity");
    //     $j->setFake();

    //     $q->addJoin($j, "communities");
    //     $q->addVariable("numItems","count(AMGroupMembers::codeUser)");
    //     $q->groupBy("AMCommunities::code");
    //     $q->setOrder("numItems desc");
    //     $q->setLimit(0,$limit);
    //     $q->setFilter("AMCommunities::status = '".AMCommunities::ENUM_STATUS_AUTHORIZED."'");

    //    return $q->execute();

    //nova consulta apos criacao dos grupos:
    // SELECT Communities.*, count(*) AS numMembers from GroupMember LEFT JOIN Groups ON Groups.codeGroup=GroupMember.codeGroup INNER JOIN Communities ON Groups.codeGroup=Communities.codeGroup GROUP BY Groups.codeGroup ORDER BY 'numMembers' DESC LIMIT 0, 5 

        $q = new CMQuery('AMCommunities');

        $j = new CMJoin(CMJoin::LEFT);
        $j->setClass('AMGroup');
        $j->on("AMGroup::codeGroup = AMCommunities::codeGroup");

        $j1 = new CMJoin(CMJoin::INNER);
        $j1->setClass('AMGroupMember');
        $j1->on("AMGroupMember::codeGroup = AMCommunities::codeGroup");

        $q->addJoin($j, "group");
        $q->addJoin($j1, "communities");
        $q->addVariable("numMembers", "count(*)");
        $q->setFilter("AMGroupMember::status = 'ACTIVE'");
        $q->groupBy("AMGroup::codeGroup");
        $q->setOrder("numMembers DESC");
        $q->setLimit(0,5);

        return $q->execute();

    }

    public function listNewComminities($limit=3)
    {
        $q = new CMQuery('AMCommunities');
        $q->setOrder("time ASC");
        $q->setLimit(0,$limit);
        return $q->execute();
    }


  /**
   * Lista as ultimas novidades das comunidades
   */
    public function listLastCommunitiesNews()
    {
        $q = new CMQuery('AMCommunities');

        $j = new CMJoin(CMJoin::INNER);
        $j->setClass('AMCommunityNews');
        $j->on("Communities.code = CommunityNews.codeCommunity");

        $q->addJoin($j, "news");

        $q->setLimit(0,5);
        $q->setOrder("CommunityNews.time desc");
        $q->setFilter("Communities.status = '".AMCommunities::ENUM_STATUS_AUTHORIZED."'");

        return $q->execute();

    }

  /**
   *Lista de comunidades
   */
    public function listCommunities()
    {
        $q = new CMQuery('AMCommunities');
        $q->setOrder("name asc");
//     $q->setFilter("AMCommunities::status = '".AMCommunities::ENUM_STATUS_AUTHORIZED."'");

        return $q->execute();
    }

  /**
   *Lista todas as comunidades em partes
   */
    public function listAllCommunities($ini=0, $length=5)
    {
        $q = new CMQuery('AMCommunities');
        $result = array();
        $q->setFilter("Communities.status = '".AMCommunities::ENUM_STATUS_AUTHORIZED."'");
        $q->setCount();
        $result['count'] = $q->execute();

        $q = new CMQuery('AMCommunities');
        $q->setOrder("name asc");
        $q->setFilter("Communities.status = '".AMCommunities::ENUM_STATUS_AUTHORIZED."'");
        $q->setLimit($ini, $length);
        $result[] = $q->execute();
        return $result;
    }


  /**
   *Busca de comunidades
   */
    public function searchCommunities($searchString, $ini=0, $length=10)
    {
        $s = new CMSearch('AMCommunities');
        $s->addSearchFields("AMCommunities::name","AMCommunities::description");
        $s->setSeachString($searchString);
        $s->setFilter("AMCommunities::status='".AMCommunities::ENUM_STATUS_AUTHORIZED."'");

        $q = clone $s;
        $q->setCount();
        $count = $q->execute();

        if(!empty($final)) {
            $s->setLimit($init, $final);
        }
        
        return array("count"=>$count, $s->execute());

    }


    
  /**
   * Friends no AMADIS
   */  
    public function listInvitationUsers()
    {
        $q = new CMQuery('AMFriend');
        $j = new CMJoin('AMUser');
        $q->setFilter("Friends.codeUser = ".$_SESSION['user']->codeUser);
    }

  /**
   * Lista usuarios que nao convidei para serem meus amigos
   */
    public function listNotMyFriendsUsers()
    {
        $q = new CMQuery('AMUser');
        $j = new CMJoin(CMJoin::INNER);
        $j->on("Friends.codeUser != User.codeUser");
        $j->setClass('AMFriend');
        $q->addJoin($j, "friend");
        $q->setFilter("User.codeUser != ".$_SESSION['user']->codeUser." AND Friends.codeUser != ".$_SESSION['user']->codeUser." AND codeFriend != ".$_SESSION['user']->codeUser);

        return $q->execute();
    }


  /**
   * Obtem os usuarios que estao conectados no ambiente
   *
   * Toda a vez que um usuario autentica-se no AMADIS o CMEnvironment grava uma informacao de tempo
   * referente a ele. Essa informacao e associada com o session_id da secao do browser. Depois de
   * um timeout definidio no arquivo de config.xml, se o usuario nao retornar a navegar, sua sessao e
   * dada como encerrada e o flagEnded e setado para 1.
   * 
   * @return class Retorna uma CMContainer com as informacoes de sessao de cada usuario. Note que nao sao retornados os AMUsers, esses devem ser construido pelos usuarios a partir da propriedade CMEnvSession->codeUser.
   */
    public function getOnLineUsers($camposProj="")
    {
        
        $q = new CMQuery('CMEnvSession');

    //$filter = "EnvSession.flagEnded = '".CMEnvSession::ENUM_FLAGENDED_NOT_ENDED."'";
        $filter  = "Friends.codeUser = ".$_SESSION['user']->codeUser;//." AND EnvSession.timeEnd < ".CMEnvSession::getTimeout(time());
        $filter .= " AND Friends.status = '".AMFriend::ENUM_STATUS_ACCEPTED."'";
        $filter .= " AND EnvSession.flagEnded = '".CMEnvSession::ENUM_FLAGENDED_NOT_ENDED."'";

        $q->setProjection("EnvSession.*");

        $j1 = new CMJoin(CMJoin::INNER);
        $j1->setClass('AMFriend');
        $j1->on("Friends.codeFriend = EnvSession.codeUser");

        $q->addJoin($j1, "friends");

        $q->setFilter($filter);
    //$q->setOrder("EnvSession.timeEnd DESC");
        $q->groupBy("EnvSession.codeUser");

        return $q->execute();

    }

  /** 
   * Consult in the database if a user is on-line.
   **/
    public function checkIsOnLine($codeUser)
    {
        $q = new CMQuery('CMEnvSession');
        $q->setFilter("codeUser = $codeUser AND flagEnded = '".CMEnvSession::ENUM_FLAGENDED_NOT_ENDED."' AND timeEnd > ".CMEnvSession::getTimeOut(time()));
        $result = $q->execute();
        if(!empty($result->items)) {
            $session = array_pop($result->items);
            return $session->visibility;
        }else return 0;
    }

    public function getNumOnlineUsers()
    {
        $lst = $this->getOnlineUsers();
        return $lst->numRecs;
    }


    public function getGroupsParents(CMContainer $groups)
    {
        if($groups->count()==0)  return new CMContainer;

        $q = new CMQuery(CMGroup);
        $f = "";
        foreach($groups as $group) {
            if(!empty($f)) $f.= " or ";
            $f.= "(CMGroup::codeGroup=$group->codeGroup)";
        }
        $q->setFilter($f);

        $j1 = new CMJoin(CMJoin::LEFT);
        $j1->setClass(AMProject);
        $j1->on("AMProject::codeGroup=CMGroup::codeGroup");

        $q->addJoin($j1,"project");

        $j2 = new CMJoin(CMJoin::LEFT);
        $j2->setClass(AMCommunities);
        $j2->on("AMCommunities::codeGroup=CMGroup::codeGroup");

        $q->addJoin($j2,"communitie");

        return $q->execute();
    }


  /**
   * The above function are used to summarize data about users, projects and
   * communities.
   **/
    public function listSummaryUsersInteraction()
    {
        $q = new CMQuery('AMUser');


        $j1 = new CMJoin(CMJoin::LEFT);
        $j1->setClass(AMForumMessage);
        $j1->on('AMForumMessage::codeUser=AMUser::codeUser');
        $j1->setFake();

        $j2 = new CMJoin(CMJoin::LEFT);
        $j2->setClass(AMChatMessages);
        $j2->on('AMChatMessages::codeSender=AMUser::codeUser');
        $j2->setFake();

        $j3 = new CMJoin(CMJoin::LEFT);
        $j3->setClass(AMBlogPost);
        $j3->on('AMBlogPost::codeUser=AMUser::codeUser');
        $j3->setFake();

        $q->addJoin($j1,'fake1');
        $q->addJoin($j2,'fake2');
        $q->addJoin($j3,'fake3');

        $q->addVariable("numForumMessages","count(AMForumMessage::code)");
        $q->addVariable("numChatMessages","count(AMChatMessages::codeMessage)");
        $q->addVariable("numBlogPost","count(AMBlogPost::codePost)");

        $q->groupby('AMUser::codeUser');

        return $q->execute();
    }

  /**
   * This function returns an summary of all interation in a project.
   *
   * The projects_exportXML.php script uses this function to export an 
   * summary of the interaction that is being happening in a project.
   * This information is used by the ProjectPulse applet to render
   * a graphical representation of the projects actualy registered
   * in the system, giving a instant vision of the state of the system.
   *  
   * @see projects_exportXML.php
   **/
    public function listSumaryProjects()
    {
        $q = new CMQuery('AMProject');
        $q->setOrder("hits desc");

        $j = new CMJoin(CMJoin::LEFT);
        $j->setClass(CMGroup);
        $j->on('CMGroup::codeGroup=AMProject::codeGroup');
        $j->setFake();

        $j2 = new CMJoin(CMJoin::LEFT);
        $j2->setClass(CMGroupMember);
        $j2->on('CMGroup::codeGroup=CMGroupMember::codeGroup');
        $j2->setFake();

        $j3 =  new CMJoin(CMJoin::LEFT);
        $j3->setClass(AMProjectForum);
        $j3->on('AMProjectForum::codeProject=AMProject::codeProject');
        $j3->setFake();

        $j4 = new CMJoin(CMJoin::LEFT);
        $j4->setClass(AMForum);
        $j4->on('AMProjectForum::codeForum=AMForum::code');
        $j4->setFake();

        $j5 = new CMJoin(CMJoin::LEFT);
        $j5->setClass(AMForumMessage);
        $j5->on('AMForum::code=AMForumMessage::codeForum');
        $j5->setFake();

        $q->addJoin($j,'fake1');
        $q->addJoin($j2,'fake2');
        $q->addJoin($j3,'fake3');
        $q->addJoin($j4,'fake4');
        $q->addJoin($j5,'fake5');


        $q->addVariable("numMembers","count(CMGroupMember::codeUser)");
        $q->addVariable('numForumMessages','count(AMForumMessage::code)');
        $q->addVariable('lastTimeForumMessage','max(AMForumMessage::timePost)');
        $q->groupBy('AMProject::codeGroup');


        return $q->execute();
    }

}