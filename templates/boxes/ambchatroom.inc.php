<?
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBChatRoom extends CMHTMLPage {
  protected $room, $toolName;

  public function __construct(AMChatRoom $room, $toolName="") {
    parent::__construct();
    $this->room = $room;
    $this->toolName = $toolName;
    $this->requires("chat.css", self::MEDIA_CSS);
    $this->requires("lib.js", self::MEDIA_JS);
    $this->id = "chatWindow";
  }

  public function __toString() {
    global $_CMAPP;
    parent::add("<!-- header flutuante -->");
    parent::add("<div class='chatheader'>");

    parent::add("<div class='zigsaw'><img src='$_CMAPP[images_url]/img_chat_01.gif' width='142' height='74'></div>");

    parent::add("<div class='infoheader'>");

    parent::add("<span class='titchat'>");
    parent::add("<img src='$_CMAPP[images_url]/img_chat_balao.gif'>&nbsp;");
    parent::add("<span class='tit_chat'> <b>".$this->room->name."</b></span");
    //parent::add("<br>no projeto <a href='#' class='local'>Portas da Felicidade</a>.</span>");
    parent::add("</div>");

    $script = "window.close();";

    parent::add("<div class='btnsair'><img src='$_CMAPP[images_url]/img_chat_bt_sair.gif' onClick='$script'></div>");
    parent::add("</div>");
    parent::add("</div>");
    
    parent::add("<!-- fim do header flutuante -->");

    parent::add("<div class='chatbox' id='chatBox'>");

    parent::add("<!-- area dialogos -->");

    parent::add("</div>");


    parent::add("<div class='sendbox'>");

    parent::add("<iframe id='sendbox' src='$_CMAPP[services_url]/chat/sendbox.php?frm_codeRoom=".$this->room->codeRoom."' style='width:99%; height: 85px; border: 0px;'></iframe>");

    parent::add("</div>");

    return parent::__toString();
  }
}

?>