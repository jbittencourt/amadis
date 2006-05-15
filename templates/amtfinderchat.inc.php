<?
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */


class AMTFinderChat extends AMChatTemplate {
  protected $to; //$para,$emotions_tr,$config_ini;
  
  public function __construct($to) {
    global $_CMAPP;

    $this->to = $to;
      
    $this->fieldRecipient = "codeRecipient";
    $this->fieldSender = "codeSender";
    $this->fieldTime = "time";

    $this->CHAT_cod_user = $_SESSION['user']->codeUser;
    $this->setSleepTime(4);


    //$em = $config_ini[Emotions];
    //$images = $config_ini[Emotions_images];

    //$this->emotions_tr = array();

//     foreach($em as $name=>$sign) {
//       $emotion = "$urlimagens/emotions/".$images[$name];
//       $this->emotions_tr[$sign] = "<img src=\"$emotion\">";
//     };



  }

  public function drawMessage($message) {
    global $_CMAPP;


    //faz a converçao dos smiles em imagens
    // array de emotions
    //$finder = $config_ini[Finder];

//     if($finder[activate_emotions]) {
//       $temp = array();
//       $men[desMensagem] = strtr($men[desMensagem],$this->emotions_tr);
//     };
    
    $hora = date("h:i",$message->time);
    if($message->codeSender == $_SESSION['user']->codeUser) { 
      $class = "messageSender";
      $color = "#0000AA";
    } else {
      $class = "messageRecipient";
      $color = "#298F23";
    }
    
    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr>\n";
    echo "<td width=\"10%\" class=\"$class\"><font color=\"$color\">".$message->users[0]->username."</font>";
    echo "<font size=-1>($hora)</font></td><td class=\"$class\">$message->message</td>\n";
    echo "</table>\n";
    
    return (strlen($message->message)/40);

  }
  
  function getNewMessages($time) {

    $messages = $_SESSION['finder']->getNewMessages($this->to,$time);
    
    if($messages->__hasItems()) {
      foreach($messages as $item) {
	
	if(($item->codeRecipient==$_SESSION['user']->codeUser) &&
	   ($item->status==AMFinderMessages::ENUM_STATUS_NOT_READ)) $item->markAsRead();

      }
    }

    return $messages;
  }

//   public function chatShutdown() {

//   }


}


?>
