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
    $id = $sender."_".$recipient;

    if(!isset($_SESSION['amadis']['FINDER_ROOM']))
      $_SESSION['amadis']['FINDER_ROOM'] = array();
    
    if(isset($_SESSION['amadis']['FINDER_ROOM'][$id]) || empty($_SESSION['amadis']['FINDER_ROOM'][$id])) {
      $_SESSION['amadis']['FINDER_ROOM'][$id] = array("sender"=>$sender,
						      "recipient"=>$recipient,
						      "time"=>time()
						      );
      
    } else {
      if(AMFinder::checkTimeOut($id)) {
	$_SESSION['amadis']['FINDER_ROOM'][$id]['time'] = time();
      }else unset($_SESSION['amadis']['FINDER_ROOM'][$id]); 
      
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
    unset($_SESSION['amadis']['FINDER_ROOM'][$idSession]);
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
  public function getOnlineUsers() {
    
    if(empty($_SESSION['environment'])) 
      throw new AMEFinderEmptyEnvironment;

    return $_SESSION['environment']->getOnlineUsers();
  }


  /**
   * Envia um mensagem para um usuario conectado
   *
   * @param string $mensagem Mensagem a ser enviada
   * @param integer $para Codigo do usuario para quem se deseja enviar um mensagem
   */
  public function sendMessage($recipient,$text) {
    $message = new AMFinderMessages;
    $message->codeSender = $_SESSION['user']->codeUser;
    $message->codeRecipient = $recipient;
    $message->message = $text;
    $message->status = AMFinderMessages::ENUM_STATUS_NOT_READ;
    $message->time = time();
    try {
      $message->save();
    }catch(CMException $e ) {
      return "not send message";
    }
    return "send message";
  }


  public function getNewMessages($sender, $recipient) {

    $idSession = $sender."_$recipient";
    
    if($this->checkTimeOut($idSession)) {
      $time = $_SESSION['amadis']['FINDER_ROOM'][$idSession]['time'];
      
      $q = new CMQuery('AMFinderMessages');
      
      $j = new CMJoin(CMJoin::INNER);
      $j->setClass('AMUser');
      $j->on("FinderMessages.codeSender = User.codeUser");
      
      $q->addJoin($j, "users");
      
      $filter  = "((codeSender = $sender AND codeRecipient = $recipient) OR ";
      $filter .= " (codeSender = $recipient AND codeRecipient = $sender)) AND FinderMessages.time > $time";
      
      $q->setOrder("FinderMessages.time ASC");
      $q->setProjection("FinderMessages.*, User.username");
      $q->setFilter($filter);
      
      $list = $q->execute();
      
      if($list->__hasItems()) {
	$this->updateTimeOut($idSession, time());
	return $this->drawMessages($list);
      } else return;
      
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
		     "username"=>$item->users[0]->username
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

  public function getNewRequests() {

    $q = new CMQuery('AMFinderMessages');

    $filter = "codeRecipient = ".$_SESSION['user']->codeUser." AND status = '".AMFinderMessages::ENUM_STATUS_NOT_READ."'";
    
    $q->setFilter($filter);

    return $q->execute();

  }


  static public function isChatOpen($codeUser) {
    if(isset($_SESSION['amadis']['finder']['contacts'][$codeUser]['chating'])) 
      return $_SESSION['amadis']['finder']['contacts'][$codeUser]['chating'];
    else return $_SESSION['amadis']['finder']['contacts'][$codeUser]['chating']=0;
  }

  public function getTime($to) {
    if(!empty($_SESSION['amadis']['finder']['contacts'][$to]['time'])) {
      return $_SESSION['amadis']['finder']['contacts'][$to]['time'];
    }
    
    return $this->time;
  }

  static public function startChat($to) {
    @session_start();
    $_SESSION['amadis']['finder']['contacts'][$to]['chating'] = 1;
  }

  public function stopChat($to) {
    @session_start();
    $_SESSION['amadis']['finder']['contacts'][$to]['chating'] = 0;

  }

  static public function putMessageInTabuList($codmes) {
    $_SESSION['amadis']['finder']['tabu_list'][$cod_mes] = 1;
  }

  static public function isMessageInTabuList($codmen) {
    return $_SESSION['finder_tmp']['tabu_list'][$cod_men];
  }

  public function __toString() {
    note($_SESSION['amadis']['finder']);
    return "";
  }
}


?>