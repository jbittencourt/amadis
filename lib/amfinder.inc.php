<?

//include_once("$rdpath/finder/rdfindermensagem.inc.php");

/**
 * Classe que implementa as funcionalidades da ferramenta Finder
 *
 * Classe que implementa as funcionalidades da ferramenta Finder
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage finder
 * @see  AMFinderMessage
 */
class AMFinder {

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
  protected $time, $timeOut;

  /**
   * Construtor da Classe Finder
   *
   * Esse construtor inicializa a ferramenta finder como uma ferramenta autentica do ambiente
   * pegando o seu ID como tal.
   */
  public function __construct() {
    //cria uma area de cache para o finder
    $_SESSION[amadis][finder] = array();
    //cria uma area de cache e gerenciamento de contatos
    $_SESSION[amadis][finder][contacts] = array();
    $this->setTime();
  }
  
  public static function getTimeOut() {
    global $_CMAPP;
    
    return (int) $_CMAPP[finder]->timeout;

  }
  
  public function getFinderTime() {
    return $this->time;
  }

  public function setTime() {
    $this->time = time();
  }

  static public function getModes() {
    global $_language;
    
    $modes[self::FINDER_NORMAL_MODE] = $_language["finder_mode_".self::FINDER_NORMAL_MODE];
    $modes[self::FINDER_BUSY_MODE] = $_language["finder_mode_".self::FINDER_BUSY_MODE];
    $modes[self::FINDER_HIDDEN_MODE] = $_language["finder_mode_".self::FINDER_HIDDEN_MODE];
    
    return $modes;
    
  }

  /**
   * Lista os usuarios conectados
   *
   * Obtem a partir da do objeto ambiente que deve estar registrado
   * na sessao quais sao os usuarios atualment conectados
   *
   * @return mixed  Retorna com uma RDLista dos usu?rios atualmente conetados ou um RDError
   * @see RDLista, RDUser, RDAmbiente
   */
  public function getOnlineUsers() {
    
    if(empty($_SESSION[environment])) 
      throw new AMEFinderEmptyEnvironment;

    return $_SESSION[environment]->getOnlineUsers();
  }



  static public function isChatOpen($codeUser) {
    if(isset($_SESSION[amadis][finder][contacts][$codeUser][chating])) 
      return $_SESSION[amadis][finder][contacts][$codeUser][chating];
    else return $_SESSION[amadis][finder][contacts][$codeUser][chating]=0;
  }

  /**
   * Envia um mensagem para um usuario conectado
   *
   * @param string $mensagem Mensagem a ser enviada
   * @param integer $para Codigo do usuario para quem se deseja enviar um mensagem
   */
  public function sendMessage($recipient,$text) {
    $message = new AMFinderMessages;
    $message->codeSender = $_SESSION[user]->codeUser;
    $message->codeRecipient = $recipient;
    $message->message = $text;
    $message->status = AMFinderMessages::ENUM_STATUS_NOT_READ;
    $message->time = time();
    try {
      $message->save();
    }catch(CMException $e ) {
      
    }

  }

  public function getNewMessages($recipient,$time) {

    $sender = $_SESSION[user]->codeUser;
    $q = new CMQuery(AMFinderMessages);
    
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass(AMUser);
    $j->on("FinderMessages.codeSender = User.codeUser");
    
    $q->addJoin($j, "users");

    $filter  = "((codeSender = $sender AND codeRecipient = $recipient) OR ";
    $filter .= " (codeSender = $recipient AND codeRecipient = $sender)) AND FinderMessages.time > $time";
    
    $q->setOrder("FinderMessages.time ASC");
    $q->setProjection("FinderMessages.*, User.username");
    $q->setFilter($filter);
    
    return $q->execute();
  }


  /**
   * Muda o modo do usuario.
   *
   * O modo do usuario deve estar dentro do array $this->modos. Ele faz a alteracao necesseria
   * no campo visibilidade da tabela sessao_ambiente(RDSessaoAMbiente). A secao do usuario esta 
   * registrada em $_SESSION[ambiente].
   *
   * @Param string $mensagem Mensagem a ser enviada
   * @param integer $para Codigo do usuario para quem se deseja enviar um mensagem
   */
  public function changeMode($mode) {
    @session_start();
    if(!empty($_SESSION[session])) {
     
      $_SESSION[session]->visibility = $mode;
      $_SESSION[session]->save();
      $this->mode = $mode;
    }
    else {
      throw new AMEFinderEmptyEnvironment;
    };
      
    return 1;

  }


  public function getNewRequests() {

    $q = new CMQuery(AMFinderMessages);

    $filter = "codeRecipient = ".$_SESSION[user]->codeUser." AND status = '".AMFinderMessages::ENUM_STATUS_NOT_READ."'";
    
    $q->setFilter($filter);

    return $q->execute();

    $ret = array();
    
    if($result->__hasItems()) {
      foreach($result as $item) {
	
	if(!self::isChatOpen($item->codeSender)) {
	  $ret[] = $item;
	  if(empty($_SESSION[amadis][finder][contacts][$item->codeSender][time]) ||
	     ($_SESSION[amadis][finder][contacts][$item->codeSender][time]>$item->time)) {
	    $_SESSION[amadis][finder][contacts][$item->codeSender][time] = $item->time;
	  }
		 
	}
      }
    }

    return $ret;
  }


  public function getTime($to) {
    if(!empty($_SESSION[amadis][finder][contacts][$to][time])) {
      return $_SESSION[amadis][finder][contacts][$to][time];
    }
    
    return $this->time;
  }

  static public function startChat($to) {
    @session_start();
    $_SESSION[amadis][finder][contacts][$to][chating] = 1;
  }

  public function stopChat($to) {
    @session_start();
    $_SESSION[amadis][finder][contacts][$to][chating] = 0;

  }

  static public function putMessageInTabuList($codmes) {
    $_SESSION[amadis][finder][tabu_list][$cod_mes] = 1;
  }

  static public function isMessageInTabuList($codmen) {
    return $_SESSION[finder_tmp][tabu_list][$cod_men];
  }

  public function __toString() {
    note($_SESSION[amadis][finder]);
    return "";
  }
};


?>