<?

include("cminterface/widgets/cmwjswin.inc.php");

class AMBChat extends CMHTMLObj {

  private $openRooms, $markedRooms;
  protected $thumb, $title, $openRoomsImg;
  private $code, $type;
  
  public function __construct() {
    global $_CMAPP, $_language;

    parent::__construct();

    $this->requires("chat.css", CMHTMLObj::MEDIA_CSS);
    $this->requires("alertbox.css", CMHTMLObj::MEDIA_CSS);
    $this->requires("chat.js", CMHTMLObj::MEDIA_JS);
    parent::addPageBegin(self::getScript("var cmapp_images_url = '$_CMAPP[images_url]';"));
    parent::addPageBegin(self::getScript("var Chat_room_url = '$_CMAPP[services_url]/chat/chatroom.php'"));
    parent::addPageBegin(self::getScript("var language_save_chat_error = '$_language[save_chat_error]';"));
    parent::addPageBegin(self::getScript("var language_save_chat_success = '$_language[save_chat_success]';"));
    parent::addPageBegin(self::getScript("var language_scheduled_to = '$_language[scheduled_to]';"));
    parent::addPageBegin(self::getScript("var language_of_day = '$_language[of_day]';"));
  }

  public function setOpenRoomsImg($img) {
    $this->openRoomsImg = $img;
  }

  public function addOpenRooms($rooms) {
    $this->openRooms = $rooms;
  }

  public function addMarkedChats($chats){
    $this->markedRooms = $chats;
  }

  public function setThumb(AMImageTemplate $img) {
    $this->thumb = $img;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

  public function setTool($type, $code) {
    $this->type = $type;
    $this->code = $code;
  }

  public function __toString() {
    
    global $_CMAPP,$_language;
    
    parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id='create_chat_box' width='570'>");
    parent::add("<tr bgcolor=\"#E1F7F9\">");
    parent::add("   <td width=\"10\"><img src=\"$_CMAPP[images_url]/box_chat_cl1.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("   <td colspan=\"2\" class=\"txttitchat\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"1\" border=\"0\"></td>");
    parent::add("   <td align=\"right\"><img src=\"$_CMAPP[images_url]/box_chat_cl2.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("<tr bgcolor=\"#E1F7F9\">");
    parent::add("   <td width=\"10\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("   <td align='center'>");

    parent::add($this->thumb);

    parent::add("<br></td>");

    parent::add("   <td valign=\"top\">");
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"7\" height=\"7\" border=\"0\"><br>");
    parent::add("<form name='create_chat' id='create_chat' onSubmit='return Chat_saveChat(this);'>");
    parent::add("       <table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">");
    parent::add("       <tr>");

    
    parent::add("<td colspan=\"2\" class=\"groupTitle\">".$this->title);
    
    parent::add("               <img src=\"$_CMAPP[images_url]/dot.gif\" width=\"7\" height=\"7\" border=\"0\">");
    parent::add("           </td>");
    parent::add("       </tr>");

    //formulario
    parent::add("<!-- form -->");

    parent::add("<tr>");
    parent::add("<td class='chat_header'>");

    parent::add("&raquo; $_language[enter_room_name]<br>");
    parent::add("</td>");
    parent::add("<td>");
    parent::add("<input type='text' id='frm_room_name' onChange=\"Chat_clearAlert(this.id);\">");
    parent::add("</td>");
    parent::add("</tr>");
    parent::add("<tr>");
    parent::add("<td class='chat_header'>");
    parent::add("&raquo; $_language[enter_room_subject]<br>");
    parent::add("</td>");
    parent::add("<td>");
    parent::add("<input type='text' id='frm_room_subject' onChange=\"Chat_clearAlert(this.id);\">");
    parent::add("</td>");
    parent::add("</tr>");
  
    parent::add("</td>");
    parent::add("</tr>");

    parent::add("</table>");

    parent::add("</td>");
    parent::add("<td width=\"10\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("</tr>");

    parent::add("<tr bgcolor='#E1F7F9'>");
    parent::add("<td></td><td align='right'>");
   
    $script  ="var agenda = AM_getElement('agenda');";
    $script .="if(agenda.style.display=='none'){ ";
    $script .="   agenda.style.display='' ";
    $script .="}else{ ";
    $script .="   agenda.style.display='none' ";
    $script .="};";
    $script .= "if(checkDate == false) checkDate=true; else checkDate=false;";
    
    parent::addPageBegin(self::getScript("var checkDate=false;"));

    parent::add("</td>");
    parent::add("<td><table width='100%'><tr><td width='50%'>");
    parent::add("<img src='$_CMAPP[imlang_url]/bt_chat_agendar.gif' onClick=\"$script\">");
    parent::add("</td><td>");
    parent::add("<input type='submit' value='$_language[create_chat_room]'>");
    parent::add("<input type='hidden' id='frm_code' value='$this->code'>");
    parent::add("<input type='hidden' id='frm_type' value='$this->type'>");
    parent::add("</td></tr></table></td><td></td>");
    parent::add("</tr>");

    parent::add("<tr bgcolor='#E1F7F9'><td></td><td colspan='2' align='right'>");
    parent::add("<br><div id='agenda' style='display: none;'>");
    parent::add("$_language[init_date_chat]: ");

    $bDate = new CMWDate("frm_beginDate", "", "d/m/Y h:i");
    $bDate->setFormName("create_chat");
    $bDate->setCalendarOn();

    parent::add($bDate);
    
    parent::add("<br>$_language[end_date_chat]: ");

    $iDate = new CMWDate("frm_endDate", "", "d/m/Y h:i");
    $iDate->setFormName("create_chat");
    $iDate->setCalendarOn();

    parent::add($iDate);

    parent::add("<br>$_language[infinity_chat]: <input id='frm_infinity' type='checkbox' value='1'>");
    parent::add("</div>");
    parent::add("</td><td></td></tr>");

    parent::add("<tr>");
    parent::add("   <td><img src=\"$_CMAPP[images_url]/box_chat_cl3.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    
    parent::add("   <td colspan=\"2\" bgcolor=\"#E1F7F9\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"1\" border=\"0\"></td>");
    parent::add("   <td><img src=\"$_CMAPP[images_url]/box_chat_cl4.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("</table>");
    parent::add("</form>");

    parent::add("<!-- end_form -->");

    parent::add("<br><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"20\" height=\"15\" border=\"0\"><br>");
    

    parent::add("<img src=\"$_CMAPP[imlang_url]/$this->openRoomsImg\"><br>");
    parent::add("<img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"8\" border=\"0\"><br>");

    parent::add("<div id='openedChatRooms' style='margin: 5px; width: 60%;'>");
    //salas abertas
    if (!empty($this->openRooms)) {
      foreach($this->openRooms as $item){
	
	parent::add("<div id='room_$item->codeRoom'>");
	parent::add("<img width='31' height='15' border='0' src='$_CMAPP[images_url]/bt_chat_balao.gif'>");

	parent::add("<a href='#' ");
	parent::add("onClick=\"Chat_openChat($item->codeRoom, 'project', '$_CMAPP[services_url]/chat/chatroom.php');\"");
	parent::add(" class='linkchat'>");

	parent::add("<b>$item->name</b> - ");
	parent::add((($count = $item->countRoomUsers()) == 0 ?  $_language['no_users_inside'] :
		     (($count = $item->countRoomUsers()) == 1 ? $_language['one_user_inside'] : 
		      $count." $_language[users_inside]")
		     ));
	parent::add("</a></div>");

      }
    } else {
      parent::add("<div id='no_opened_chats'>&nbsp;&nbsp; $_language[no_chats_open]</div>");
    }
      
    //final da lista de salas abertas
    parent::add("</div>");

    parent::add("<br><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"20\" height=\"15\" border=\"0\"><br>");
    parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100\">");
    parent::add("<tr>");
    parent::add("   <td colspan=\"2\" valign=\"top\"><img src=\"$_CMAPP[images_url]/pt-br/box_chat_agenda_amadis.gif\" width=\"431\" height=\"34\" border=\"0\"></td>");
    parent::add("   <td align=\"right\"><img src=\"$_CMAPP[images_url]/box_chat_agenda_amadis2.gif\"></td>");
    parent::add("</tr>");
    parent::add("<tr>");
    parent::add("   <td width=\"15\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"15\" height=\"10\" border=\"0\"></td>");
    parent::add("   <td valign=\"top\">");

    parent::add("<div class='textoverde' id='scheduledChatRooms' style=' margin: 5px; width: 60%'>");

    //salas agendadas    
    if(!empty($this->markedRooms)){
      foreach($this->markedRooms as $item){

	$date = getDate($item->beginDate);
	
	parent::add("<div class='textoverde' id='room_$item->codeRoom'>");
	parent::add("<b>$item->name</b> <br>");
	parent::add("$_language[scheduled_to]&nbsp; ");
	parent::add("<span class='datachat'>$date[hours]:$date[minutes] $_language[of_day] $date[mday]/$date[mon]/$date[year]</span><br>");
	parent::add("por ".$item->user[0]->name."<br><img src='$_CMAPP[images_url]/dot.gif' height='10' width='10'><br>");
	parent::add(new AMDotline);
      }
    }
    else{
      parent::add("<div id='no_scheduled'>&nbsp;&nbsp; $_language[no_marked_chats]</div>");
    }
    //fim salas agendadas
    parent::add("</div>");

    parent::add("   </td>");
    parent::add("</table>");
    //notelastquery();
    return parent::__toString();
    
  }
  
}

?>