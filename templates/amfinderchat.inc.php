<?


class AMFinderChatTemplate extends RDChatTemplate {
  var $para,$emotions_tr,$config_ini;
  
  function AMFinderChatTemplate($para) {
    global $config_ini,$urlimagens;

    $finder = $config_ini[Finder];

    $this->para = $para;

    $this->campoDest = "codDestino";
    $this->campoSender = "codRemetente";
    $this->campoTempo = "tempo";

    $this->sala = 0;
    $this->CHAT_cod_user = $_SESSION[usuario]->codUser;
    $this->setSleepTime(4);


    $em = $config_ini[Emotions];
    $images = $config_ini[Emotions_images];

    $this->emotions_tr = array();

    foreach($em as $name=>$sign) {
      $emotion = "$urlimagens/emotions/".$images[$name];
      $this->emotions_tr[$sign] = "<img src=\"$emotion\">";
    };



  }

  function stopChat() {
    //recomessa a sessao que havia sido fechada e registra o final do chat.
    @session_start();
    $_SESSION[finder]->stopChat($this->para);
   
  }


  function drawMessage($men) {
    global $config_ini,$urlimagens;


    //faz a converçao dos smiles em imagens
    // array de emotions
    $finder = $config_ini[Finder];

    if($finder[activate_emotions]) {
      $temp = array();
      $men[desMensagem] = strtr($men[desMensagem],$this->emotions_tr);
    };



    $user = new AMUser($men[codRemetente]);
    
    $hora = date("h:i",$men[tempo]);

    echo "<table width=\"100%\"><tr>";
    echo "<td width=\"10%\"><font color=\"#0000AA\">$user->nomUser</font><font size=-1>($hora)</font></td><td>$men[desMensagem]</td>";
    echo "</table>";

    return (strlen($mensagem)/40);

  }

  function getNewMessages($time) {

    $mens = $_SESSION[finder]->getNewMessages($this->para,$time);
    $user = $_SESSION[usuario];

    if(!empty($mens->records)) {
      foreach($mens->records as $men) {
	
	if(($men->codDestinatario==$user->codUser) and ($men->flaLida==0)) $men->marcaLida();
     
	$ret[] = $men->toArray();
	unset($men);
      };
    };


    return $ret;
  }




};


?>
