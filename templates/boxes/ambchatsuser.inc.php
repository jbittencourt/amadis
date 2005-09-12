<?

class AMBChatsUser extends AMColorBox {
  
  private $cProjects, $cDiary, $cMessages;

  public function __construct() {
    global $_CMAPP;
    parent::__construct("$_CMAPP[imlang_url]/box_chats_amadis.gif",AMColorBox::COLOR_BOX_BLUE);

    $this->salas_p = $_SESSION[user]->getUserProjectChats();
    //$this->salas_c = $_SESSION[user]->getUserCommunityChats();
  }
  
  public function __toString() {
    global $_language,$_CMAPP;
    $lang = $_CMAPP[i18n]->getTranslationArray("chat");
    
    if($this->salas_p->__hasItems()) {
      $this->empty = false;
      parent::add("<b>$_language[projects]</b><br>");
      foreach($this->salas_p as $item) {
	$chatWindow = new CMWJSWin("$_CMAPP[services_url]/chat/chatroom.php?frm_codSala=".$item->codSala."&conexao=$flag",
				   $item->codSala,540,620);
	$chatWindow->setResizeOff();
	parent::add(" &raquo; $lang[box_chat]<a onClick=\"");
	parent::add($chatWindow->__toString()."\" class=\"bluebox cursor\">$item->nomSala</a> &nbsp; <br>");
      }
      parent::add(new AMDotline);
    } else $this->empty = true;

//     if($this->salas_c->__hasItems()) {
//       parent::add("<b>$_language[communities]</b><br>");
//       foreach($this->salas_p as $item) {
// 	$chatWindow = new CMWJSWin("$_CMAPP[services_url]/chat/chatroom.php?frm_codSala=".$item->codSala."&conexao=$flag",
// 				   $item->codSala,540,620);
// 	$chatWindow->setResizeOff();
// 	parent::add(" &raquo; $lang[box_chat]<a onClick=\"");
// 	parent::add($chatWindow->__toString()."\" class=\"bluebox cursor\">$item->nomSala</a> &nbsp; <br>");
//       }
      //parent::add(new AMDotline);
    //    }    
    if($this->empty) parent::add($_language[no_chats]);
    return parent::__toString();
  
  }


}


?>