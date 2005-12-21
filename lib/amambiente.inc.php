<?


class AMAmbiente extends CMEnvironment {



  public function getStats() {
    $q1 = new CMQuery('AMProjeto');
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

  public function listAreas() {
    $q = new CMQuery('AMArea');
    $q->setOrder("nomArea asc");
    return $q->execute();
  }


  public function listProjectsByArea($codArea, $ini=0, $length=5) {
    $q= new CMQuery('AMProjeto');
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass('AMProjetoArea');
    $j->on("AMProjeto::codeProject = AMProjetoArea::codProjeto");

    $result = array();
    
    $q->addJoin($j, "area");
    $q->setOrder("time desc");
    $q->setFilter("codArea =". $codArea);
    $q->setCount();
    $result['count'] = $q->execute();
    
    $q = new CMQuery('AMProjeto');
    $j1 = new CMJoin(CMJoin::INNER);
    $j1->setClass('AMArea');
    $j1->on("AMArea::codArea = AMProjetoArea::codArea");
    
    $q->addJoin($j, "areas");
    $q->addJoin($j1, "area");
    $q->setOrder("time desc");
    $q->setFilter("AMArea::codArea =". $codArea);
    $q->setLimit($ini, $length);
    $result[] = $q->execute();
    return $result;
  }

  public function searchProjects($searchString, $ini=0, $length=10) {
    $s = new CMSearch('AMProjeto');
    $s->addSearchFields("AMProjeto::title","AMProjeto::description");
    $s->setSeachString($searchString);

    $q = clone $s;
    $q->setCount();
    $count = $q->execute();

    if(!empty($final)) {
      $s->setLimit($init, $final);
    }
    
    return array("count"=>$count, $s->execute());
  }

  public function listAllProjects($ini=0, $length=5){
    $q = new CMQuery('AMProjeto');
    $result = array();
    $q->setCount();
    $result['count'] = $q->execute();

    $q = new CMQuery('AMProjeto');
    $q->setOrder("title asc");
    $q->setLimit($ini, $length);
    $result[] = $q->execute();
    return $result;
  }


  public function listTopProjects($limit=5) {
    $q = new CMQuery('AMProjeto');
    $q->setOrder("hits desc");
    $q->setLimit(0, $limit);
    return $q->execute(); 
  }

  public function listNewProjects($limit=3) {
    $q = new CMQuery('AMProjeto');
    $q->setOrder("time desc");
    $q->setLimit(0, $limit);
    return $q->execute(); 
  }


  function listaCidades() {
    
    $q = new CMQuery('AMCidade');
    $j = new CMJoin(CMJoin::NATURAL);
    $j->setClass('AMEstado');
    
    $q->addJoin($j, "estado");
    
    $q->setOrder("nomCidade asc");
    $q->setDistinct();
       
    return $q->execute();
  }

  public function listaAvisos() {
    $tempo = time();
    $q = new CMQuery('AMAviso');    
    return $q->execute();
  }


  /**
   * Listagem e busca de usuarios.
   */
  
  public function countUsers() {
    $q = new CMQuery('AMUser');
    $q->setCount();
    return $q->execute();
  }

  public function countSearchResult($q){
    $q->setCount();
    return $q->execute();
  }
  
  public function listUsers() {
    
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

  public function searchUsers($searchString, $init="", $final="") {
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
  public function listLastDiaryPosts() {
    $q = new CMQuery('AMDiarioPost');
    
    $j2 = new CMJoin(CMJoin::INNER);
    $j2->setClass('AMUser');
    $j2->on("AMDiarioPost::codeUser = AMUser::codeUser");

    $q->addJoin($j2, "autor");
    
    $q->setLimit(0,5);
    $q->setOrder("AMDiarioPost::tempo desc");
    return $q->execute();
    
  }

  /**
   * List the diary profiles of the users that have alredy blogged, ordered by the last post message
   **/
  public static function listDiaries($lower_limit=0,$upper_limit=0) {
    
    $q = new CMQuery('AMUser');
    
    $j1 = new CMJoin(CMJoin::LEFT);
    $j1->setClass('AMDiarioProfile');
    $j1->on("AMDiarioProfile::codeUser=AMUser::codeUser");

    $j2 = new CMJoin(CMJoin::INNER);
    $j2->setClass('AMDiarioPost');
    $j2->on("AMDiarioPost::codeUser=AMUser::codeUser");
    $j2->setFake();
    
    $q->addJoin($j1,"profile");
    $q->addJoin($j2,"");


    //count the total number of rows for paggination
    $c = clone $q;
    $c->setCount();
    $count = $c->execute();

    //confirure the query with extra params
    $q->addVariable("lastPostTime","MAX(AMDiarioPost::tempo)");
    $q->groupBy("AMUser::codeUser");
    $q->setOrder("AMDiarioPost::tempo desc");

    if(!empty($upper_limit)) {
      $q->setLimit($lower_limit,$upper_limit);
    }

    return array("count"=>$count,"data"=>$q->execute());
  }

  /**
   *Lista 5 ultimos usuarios logados no exato momento
   */
  public function listLastUsersLogeds($numRows=5) {
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
  public function listBiggerCommunities($limit=5) {
    $q = new CMQuery('AMCommunities');
    
    $j = new CMJoin(CMJoin::LEFT);
    $j->setClass('AMCommunityMembers');
    $j->on("AMCommunities::code = AMCommunityMembers::codeCommunity");
    $j->setFake();

    $q->addJoin($j, "communities");
    $q->addVariable("numItems","count(AMCommunityMembers::codeUser)");
    $q->groupBy("AMCommunities::code");
    $q->setOrder("numItems desc");
    $q->setLimit(0,$limit);
    $q->setFilter("AMCommunities::status = '".AMCommunities::ENUM_STATUS_AUTHORIZED."'");

    return $q->execute();
  }

  public function listNewComminities($limit=3) {
    $q = new CMQuery('AMCommunities');
    $q->setOrder("time DESC");
    $q->setLimit(0,$limit);
    return $q->execute();
  }


  /**
   *Lista as ultimas novidades das comunidades
   */
  public function listLastCommunitiesNews() {
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
  public function listCommunities() {
    $q = new CMQuery('AMCommunities');
    $q->setOrder("name asc");
    $q->setFilter("Communities.status = '".AMCommunities::ENUM_STATUS_AUTHORIZED."'");

    return $q->execute();
  }

  /**
   *Lista todas as comunidades em partes
   */
  public function listAllCommunities($ini=0, $length=5){
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
  public function searchCommunities($searchString, $ini=0, $length=10) {
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
   *Lista os projetos de uma comunidade
   */
  public function listProjectsCommunity($codeCommunity, $ini=0, $length=5) {
    $q= new CMQuery('AMProjeto');
    
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass('AMCommunityProjects');
    $j->on("Projects.codeProject = CommunityProjects.codeProject");

    $j1 = new CMJoin(CMJoin::INNER);
    $j1->setClass('AMCommunities');
    $j1->on("Communities.code = CommunityProjects.codeCommunity");

    $result = array();
    
    $q->addJoin($j, "pCommunity");
    $q->addJoin($j1, "community");
    $q->setCount();
    $q->setFilter("Communities.status = '".AMCommunities::ENUM_STATUS_AUTHORIZED."'");
    $result['count'] = $q->execute();
    
    $q = new CMQuery('AMProjeto');

    $q->addJoin($j, "pCommunity");
    $q->addJoin($j1, "community");

    $q->setFilter("CommunityProjects.codeCommunity =". $codeCommunity);
        
    $q->groupby("Projects.codeProject");
    $q->addVariable("numItems","count( Projects.codeProject)");
    
    $q->setLimit($ini, $length);
    $q->setFilter("Communities.status = '".AMCommunities::ENUM_STATUS_AUTHORIZED."'");
    $result[] = $q->execute();
    return $result;
  }

  /**
   *Lista requisicoes de entrada na comunidade
   */
  public function listMembersJoinCommunity($codeCommunity) {
    $q = new CMQuery('AMCommunityMemberJoin');
    
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass('AMUser');
    $j->on("CommunityMemberJoin.codeUser = User.codeUser");

    $q->addJoin($j, "user");
    
    $q->setFilter("");

    return $q->execute();
    
  }
  
  /**
   *Friends no AMADIS
   */  
  public function listInvitationUsers() {
    $q = new CMQuery('AMFriend');
    $j = new CMJoin('AMUser');
    $q->setFilter("Friends.codeUser = ".$_SESSION['user']->codeUser);
  }

  /**
   *Lista usuarios que nao convidei para serem meus amigos
   */
  public function listNotMyFriendsUsers() {
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
   * um timeout definidio no arquivo de config.ini, se o usuario nao retornar a navegar, sua sessao e
   * dada como encerrada e o flagEncerrado e setado para 1.
   * @return class Retorna uma CMContainer com as informacoes de sessao de cada usuario. Note que nao sao retornados os AMUsers, esses devem ser construido pelos usuarios a partir da propriedade CMEnvSession->codeUser.
   */
  public function getOnLineUsers($camposProj="") {
    
    $q = new CMQuery('CMEnvSession');
    
    //$filter = "EnvSession.flagEnded = '".CMEnvSession::ENUM_FLAGENDED_NOT_ENDED."'";
    $filter  = "Friends.codeUser = ".$_SESSION['user']->codeUser;//." AND EnvSession.timeEnd < ".CMEnvSession::getTimeout(time());
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

  /** Checa se um usuario estah onLine
   * 
   */
  public function checkIsOnLine($codeUser) {
    $q = new CMQuery('CMEnvSession');
    $q->setFilter("codeUser = $codeUser AND flagEnded = '".CMEnvSession::ENUM_FLAGENDED_NOT_ENDED."' AND timeEnd > ".CMEnvSession::getTimeOut(time()));
    $result = $q->execute();
    if(!empty($result->items)) {
      $session = array_pop($result->items);
      return $session->visibility;
    }else return 0;
  }

  public function getNumOnlineUsers() {
    $lst = $this->getOnlineUsers();
    return $lst->numRecs;
  }

  /**
   * CHAT
   */
  public function listaChatsFuturos($data,$tipo,$cod){
    $ju = new CMJoin(CMJoin::INNER);
    $ju->setClass('AMUser');
    $ju->on("chat_sala.codeUser = User.codeUser");
    
    switch($tipo){

    case "Comunidade":
      $query = new CMQuery('AMChat');
      $j = new CMJoin(CMJoin::INNER);
      $j->on("chat_sala.codSala = comunidadeChats.codSala");
      $j->setClass('AMComunidadeChats');
      $query->addJoin($j, "aux");
      $query->addJoin($ju,"users");
      $data=$data+300;
      $sql = "datInicio>$data AND codComunidade=".$cod;
      $query->setFilter($sql);
      $ret = $query->execute();
      
      //notelastquery();
      break;

    case "Projeto":
      $query = new CMQuery('AMChat');
      $j = new CMJoin(CMJoin::INNER);
      $j->on("chat_sala.codSala = projetoChats.codSala");
      $j->setClass(AMProjetoChats);
      $query->addJoin($j, "aux");
      $query->addJoin($ju,"users");
      $data=$data+300;
      $sql = "datInicio>$data AND codProjeto=".$cod;
      $query->setFilter($sql);
      $ret = $query->execute();

      break;
    }
    
    return $ret;

  }

  


  function listaChats($tipo,$cod){
    
    switch($tipo){

    case "Comunidade":
      $query = new CMQuery('AMChat');
      $j = new CMJoin(CMJoin::INNER);
      $j->on("chat_sala.codSala = comunidadeChats.codSala");
      $j->setClass('AMComunidadeChats');
      $query->addJoin($j, "aux");
      $tempo = time();
      $sql = "codComunidade=".$cod." AND datInicio<=$tempo AND datFim>$tempo";;
      $query->setFilter($sql);
      $ret = $query->execute();
      
      //notelastquery();
      break;



    case "Projeto":
      $query = new CMQuery('AMChat');
      $j = new CMJoin(CMJoin::INNER);
      $j->on("chat_sala.codSala = projetoChats.codSala");
      $j->setClass('AMProjetoChats');
      $query->addJoin($j, "aux");
      $tempo = time();
      $sql = "codProjeto=".$cod." AND datInicio<=$tempo AND datFim>$tempo";;
      $query->setFilter($sql);
      $ret = $query->execute();

      break;
    }
    
    return $ret;

  }
  

  public function is_user_in_chatroom($user, $salas_abertas){
    
    foreach($salas_abertas as $sala) {
      $sql = "codSala=".$sala->codSala." AND codUser=$user AND flaOnline=1";
      $query = new CMQuery('AMChatConnection');
      $query->setFilter($sql);
      $ret = $query->execute();

      if (count($ret->items)>0){
	$return = "sim";
      }
      else{
	$return ="nao";
      }
    }
    return $return;
  }

}

?>
