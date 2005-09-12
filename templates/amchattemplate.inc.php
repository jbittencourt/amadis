<?
/**
 */
/**
 * Template para a interface de um chat.
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @abstract
 * @version 0.5
 * @package rddevel
 * @subpackage interface
 * @see RDObj
 */
abstract class AMChatTemplate extends CMHTMLObj {
  public $fieldRecipient,$fieldSender;
  public $fieldTime, $sleepTime, $CHAT_cod_user;

  public function __construct() {
    parent::__construct();
    $this->sleepTime = 2;  //seta epera para 2 segundos
  }

  //essa funcao é um template e deve ser reimplementada para o chat de cada ambiente
  abstract public function drawMessage($message);

  //essa funcao é um template e deve ser reimplementada para o chat de cada ambiente
  abstract public function getNewMessages($time);
  
  //esta eh uma funcao usada para quando um chat eh enserrado
  //abstract public function chatShutdown();

  public function setSleepTime($time) {
    $this->sleepTime = $time;
  }



  //essa funcao foi retirada do site do php como uma alternativa 
  // ao connection_aborted, que atualmente (11-2-2003) encontra-se
  // quebrada. Ela provavelmente tem efeitos de performace no servidor
  // mas e melhor que nada. Quando a funcao do php voltar a funcionar
  // espero que ela seja retirada
  public function alt_connection_aborted() {
    $ip = str_replace(".", "\\.", getenv("REMOTE_ADDR"));
    $port = getenv("REMOTE_PORT");
    
    return (preg_match("/^tcp +[0-9]+ +[0-9]+ +[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}:[0-9]+ +$ip:$port +.*?$/m", `netstat -n --tcp`))?false:true;
  }

  public function mainLoop($lastMessageTime="") {
    global $_CMAPP;
    
    session_write_close();    //fecha a seção para escrita evitando sessões concorrentes, o que gera um bug no sistema
    ignore_user_abort(TRUE);
    set_time_limit(0);
    
    //register_shutdown_function(array(&$this,"chatShutdown"));

    if(empty($lastMessageTime)) $lastMessageTime = time();
    
    //imprime os cabecalhos da pagina
    echo "<html>\n";
    echo "<head>\n";
    echo "<script type=\"text/javascript\" charset=\"utf-8\" src=\"$_CMAPP[js_url]/scrollScript.js\"></script>\n";
    echo "<link rel=\"stylesheet\" href=\"$_CMAPP[css_url]/finder_mensagens.css\">\n";
    echo "</head>\n";
    echo "<body bgcolor=\"#FFFFFF\">\n";
    
    flush();
    

    //while ((!$this->alt_connection_aborted()) && (!$onlyShow) )   { 
    while ((!connection_aborted()) && (!$onlyShow) )   { 
      
      $onlyShow = $this->onlyShow;
      
      
      $messages = $this->getNewMessages($lastMessageTime); //pega as novas mensagens
            
      //sempre manda um espaco para forcar uma shutdown_function a ser chamada;
      echo " \n";
      flush();

      if ($messages->__hasItems()) {
	foreach($messages as $message) {
	  $fRecipient = $this->fieldRecipient;
	  $fSender = $this->fieldSender;
	  $fTime = $this->fieldTime;
	  
// 	  echo $message->$fRecipient;
// 	  echo $this->CHAT_cod_user;
// 	  echo $message->$fSender; 
	    
	  if ($message->$fRecipient==0 || $message->$fRecipient==$this->CHAT_cod_user ||
	      $message->$fSender==$this->CHAT_cod_user) {
	    
	    $numLines = $this->drawMessage($message);
	  }
	  $lastMessageTime = $message->$fTime;                  
	  
      	  //este eh o script que chama o scroll da tela
	  echo self::getScript("scrollTela($numLines);");  
	 	  
	  flush();     
	}
 
      }
      flush();
      sleep($this->sleepTime);
    }
 
  }

}


?>
