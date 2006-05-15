<?
/**
 * Box that list the chats that are in progress or scheduled in the projects or communities of the user 
 *
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMChat
 * @category AMBoxes
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMColorBox
 */

class AMBChatsUser extends AMColorBox {
  
  private $cProjects, $cDiary, $cMessages;

  public function __construct() {
    global $_CMAPP;
    $this->requires("chat.css", CMHTMLObj::MEDIA_CSS);
    $this->requires("chat.js", CMHTMLObj::MEDIA_JS);

    parent::__construct("$_CMAPP[imlang_url]/box_chats_amadis.gif",AMColorBox::COLOR_BOX_BLUE);

    $this->salas_p = $_SESSION['user']->getUserProjectChats();
    $this->salas_c = $_SESSION['user']->getUserCommunityChats();
  }


  /**
   * Render a list of elements that contains chat rooms
   *
   * @param object $rooms An CMContainer where each element contain a variable rooms with a list of chats.
   * @param object $htmlobj A box where this list will be rendere
   * @param string $prefix A string to be apended before each element
   * @param string $el_title A string with the name of the propertie that identifies the element
   * @return object Returns a CMHTMLObj with the rendered list
   **/
  protected function renderRoomsList($rooms,$prefix,$el_title) {
    global $_language,$_CMAPP;
    $_language = $_CMAPP['i18n']->getTranslationArray("chat");

    $htmlobj =  new CMHTMLObj;
    $htmlobj->add("<ul class='chats_list_parent'>");

    foreach($rooms as $el) {
      $title = $el->$el_title;
      $htmlobj->add("<li>$prefix $title");
      $htmlobj->add("<ul class='chats_list'>");

      $tmp = $el->rooms;
      foreach($tmp as $item) {
	$date = date($_language['hour_format'].' '. $_language['date_format'],$item->beginDate);
	if($item->beginDate>time()) {
	  $htmlobj->add("<li><img src='$_CMAPP[images_url]/icon_chat_agenda.gif'>");
	  $htmlobj->add("<b>$_language[scheduled_chat]:</b> \"$item->name\"");
	  $htmlobj->add("&nbsp; $_language[scheduled_to] &nbsp; <span class='datachat'>$date</span>");
	} else {
	  $htmlobj->add("<li><img src='$_CMAPP[images_url]/icon_arrow.gif'>");
	  $htmlobj->add("<b>$_language[in_progress_chat]");
	  $htmlobj->add("<a href='#' ");
	  $htmlobj->add("onClick=\"Chat_openChat($item->codeRoom, 'project', '$_CMAPP[services_url]/chat/chatroom.php');\"");
	  $htmlobj->add(" class='linkchat'> \"$item->name\"</a>");

	}

      }
      $htmlobj->add("</ul>");
    }
    $htmlobj->add("</ul>");
    
    return $htmlobj;
  }
  
  public function __toString() {
    global $_CMAPP;

    $_language = $_CMAPP['i18n']->getTranslationArray("chat");

    $this->empty =  true;
    if($this->salas_p->__hasItems()) {
      $this->empty = false;
      parent::add("<b>$_language[projects]</b><br>");
      parent::add($this->renderRoomsList($this->salas_p,$_language[projects],"title"));
      parent::add(new AMDotline);
    };



    if($this->salas_c->__hasItems()) {
      $this->empty = false;
      parent::add("<b>$_language[communities]</b><br>");
      parent::add($this->renderRoomsList($this->salas_c,$_language[community],"name"));
    } 


    if($this->empty) parent::add($_language['no_chats']);
    return parent::__toString();
  
  }


}


?>