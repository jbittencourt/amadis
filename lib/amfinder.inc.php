<?

/**
 * Classe que implementa as funcionalidades da ferramenta Finder
 *
 * Classe que implementa as funcionalidades da ferramenta Finder
 *
 * @author Robson Mendonça <robson@lec.ufrgs.br>
 * @access public
 * @version 0.5
 * @package AMADIS
 * @subpackage finder
 * @see  AMFinderMessage, AMFinderChatRoom
 */
class AMFinder { //implements AMCChat {

  /**
   * Constantes que definem o modo de visibilidade do finder
   * @const FINDER_NORMAL_MODE Visibilidade normal
   * @const FINDER_BUSY_MODE Ocupado eh visivel mas nao pode receber mensagens
   * @const FINDER_HIDDEN_MODE Invisivel
   */
  const FINDER_NORMAL_MODE = "VISIBLE";
  const FINDER_BUSY_MODE   = "BUSY";
  const FINDER_HIDDEN_MODE = "HIDDEN";
  
  /**
   * @var integer $modo Guarda o modo de operacao atual do finder(normal, oculto, ocupado)
   * @var array $chats_abertos Guarda todos os chats que est?o atualmente abertos no sistema
   */
  protected $mode, $openChats;
  protected $time, $timeOut, $sleepTime;

  static public function initFinder($sender, $recipient) {
    global $_CMAPP;
    
    $id = $sender."_".$recipient;

    if(!isset($_SESSION['amadis']['FINDER_ROOM']))
      $_SESSION['amadis']['FINDER_ROOM'] = array();

    if(!isset($_SESSION['amadis']['FINDER_ROOM'][$id])) {
      $_SESSION['amadis']['FINDER_ROOM'][$id] = array("sender"=>$sender,
						      "recipient"=>$recipient,
						      "time"=>time(),
						      "wait"=>array(),
						      "open"=>1
						      );
    } else {
      $time = $_SESSION['amadis']['FINDER_ROOM'][$id]['time'];
      $timeout = time()-((int) $_CMAPP['finder']->timeout);
      if($time >= $timeout) {
	$_SESSION['amadis']['FINDER_ROOM'][$id]['time'] = time();
      }else $_SESSION['amadis']['FINDER_ROOM'][$id]['open'] = 0; 
    }
  }

  
  public function checkTimeOut($id) {
    $time = $_SESSION['amadis']['FINDER_ROOM'][$id]['time'];
    $timeout = (time()-self::getTimeOut());
    if($time >= $timeout) return true;
    else return false;
  }
  
  public function updateTimeOut($session, $time) {
    $_SESSION['amadis']['FINDER_ROOM'][$session]['time'] = $time;
  }

  public function closeFinderChat($idSession) {
    $_SESSION['amadis']['FINDER_ROOM'][$idSession]['open'] = 0;
  }

  public function setSleepTime() { }

  public function getSleepTime() {
    global $_CMAPP;
    
    $sleepTime = (int) $_CMAPP['finder']->sleeptime;
    
    return $sleepTime;
  
  }

  public function addChat($type, $code) { }
  public function addUserChat($codeUser, $codeUser, $typeRoom) { }
  
  public function getTimeOut() {
    global $_CMAPP;
    $this->timeOut = (int) $_CMAPP['finder']->timeout;
    return $this->timeOut;
  }
  
  /**
   * Lista os usuarios conectados
   *
   * Obtem a partir da do objeto environment que deve estar registrado
   * na sessao quais sao os usuarios atualment conectados
   *
   * @return mixed  Retorna um CMContainer dos usu?rios atualmente conetados ou um RDError
   * @see AMuser, AMAMbiente
   */
  static function getOnlineUsers() {
    global $_CMAPP;
    if(empty($_SESSION['environment'])) 
      throw new AMEFinderEmptyEnvironment;

    $result = $_SESSION['environment']->getOnlineUsers();

    $ret = array();
    $ret['src'] = $_CMAPP['images_url'];

    if($result->__hasItems()) {

      foreach($result as $k=>$item) {
	//$ret[$k]=array();
	//$ret[$k]['visibility'] = $item->visibility;
	//$ret[$k]['codeUser'] = $item->codeUser;
	//$ret[$k]['flagEnded'] = $item->flagEnded;
	$_SESSION['amadis']['onlineusers'][$item->codeUser] = array();
	$_SESSION['amadis']['onlineusers'][$item->codeUser]['flagEnded'] = $item->flagEnded;
	$_SESSION['amadis']['onlineusers'][$item->codeUser]['visibility'] = $item->visibility;
      }
      $ret['data'] = $_SESSION['amadis']['onlineusers'];
    }else {
      foreach($_SESSION['amadis']['onlineusers'] as $k=>$item) {
	$item['flagEnded'] = CMEnvSession::ENUM_FLAGENDED_ENDED;
	$item['visibility'] = AMFinder::FINDER_NORMAL_MODE;
	$_SESSION['amadis']['onlineusers'][$k] = $item;
      }
      $ret['data'] = $_SESSION['amadis']['onlineusers'];
    }
    
    return $ret;
  }


  public function getNewMessages($sender, $recipient) {

    $idSession = $sender."_$recipient";

    if($this->checkTimeOut($idSession)) {
      $time = $_SESSION['amadis']['FINDER_ROOM'][$idSession]['time'];
      
      $q = new CMQuery('AMFinderMessages');
      
      $j = new CMJoin(CMJoin::INNER);
      $j->setClass('AMUser');
      $j->on("FinderMessages.codeSender = User.codeUser");
      
      $q->addJoin($j, "user");
      
      $filter  = "((codeSender = $sender AND codeRecipient = $recipient) OR ";
      $filter .= " (codeSender = $recipient AND codeRecipient = $sender)) AND FinderMessages.time > $time";
      
      $q->setOrder("FinderMessages.time ASC");
      $q->setProjection("FinderMessages.*, User.username");
      $q->setFilter($filter);
      
      $list = $q->execute();

      //parse na lista de mensagens em espera
      
      if(!empty($_SESSION['amadis']['FINDER_ROOM'][$idSession]['wait'])) {
	foreach($_SESSION['amadis']['FINDER_ROOM'][$idSession]['wait'] as $k=>$item) {
	  $list->add($k,unserialize($item));
	  unset($_SESSION['amadis']['FINDER_ROOM'][$idSession]['wait'][$k]);
	}
      }
      
      if($list->__hasItems()) {
	$this->updateTimeOut($idSession, time());
	return $this->drawMessages($list);
      } else return 0;
      
    } else {
      $this->closeFinderChat($idSession);
      return $this->drawMessages("conversation_timeout");
    }
  }


  public function drawMessages($messages) {
    $msg  = array();

    if($messages instanceof CMContainer) {
      foreach($messages as $item) {
	$tmp = array("responseType"=>"parse_messages",
		     "message"=>$item->message,
		     "date"=>date("h:i",$item->time),
		     "username"=>$item->user[0]->username
		     );
	
	if($item->codeSender == $_SESSION['user']->codeUser) {
	  $tmp['style'] = "messageSender";
	} else {
	  $tmp['style'] = "messageRecipient";
	}
	
	$msg[] = $tmp;
      }
    } else {
      
      switch($messages) {
      case "conversation_timeout":

	$msg[] = array("responseType"=>"finder_timeout",
		       "message"=>$messages
		       );
	break;
      }
    }
    
    return $msg;
  }

}


?>