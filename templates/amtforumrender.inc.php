<?

class AMTForumRender extends CMHTMLObj {

  protected $forum;
  protected $avaiable_themes;
  protected $themes;
  protected $show_open;
  protected $lastVisitTime = -1;

  public function __construct(AMForum $forum,$show_open=array()) {
    global $_CMAPP;

    $this->requires("forum.js",self::MEDIA_JS);

    parent::__construct();
    $this->forum = $forum;

    $this->show_open = $show_open;
    $this->avaiable_themes =2;

    $visit = $forum->lastVisit($_SESSION['user']->codeUser);
    if(!empty($visit)) {
      $this->lastVisitTime = $visit->time;
    }

    //blue theme
    $this->themes[0]['ico_level'][1] = $_CMAPP['images_url']."/forum_ico_topic.gif";
    $this->themes[0]['ico_level'][2] = $_CMAPP['images_url']."/forum_icoblue_rply2.gif";
    $this->themes[0]['ico_level'][3] = $_CMAPP['images_url']."/forum_icoblue_rply3.gif";
    $this->themes[0]['ico_level'][4] = $_CMAPP['images_url']."/forum_icoblue_rply4.gif";
    $this->themes[0]['css_base_name'] = "msg_level_A0";

    //red theme
    $this->themes[1]['ico_level'][1] = $_CMAPP['images_url']."/forum_icored_topic.gif";
    $this->themes[1]['ico_level'][2] = $_CMAPP['images_url']."/forum_icored_rply2.gif";
    $this->themes[1]['ico_level'][3] = $_CMAPP['images_url']."/forum_icored_rply3.gif";
    $this->themes[1]['ico_level'][4] = $_CMAPP['images_url']."/forum_icored_rply4.gif";
    $this->themes[1]['css_base_name'] = "msg_level_B0";


  }


  private function drawMessage($men,$theme,$level=1,$ignore_new=false) {
    global $_CMAPP, $_language;

    $obj = new CMHTMLObj;
    $nc =  $theme['css_base_name'];

    if($level>4) { 
      $nc.="4 "; 
      $img_arrow = $theme['ico_level'][4];
    
    } else { 
      $img_arrow = $theme['ico_level'][$level];
      $nc.="$level ";
    };


    $dsp = "none";
    $img_view = "bt_forum_abrir.png";
    $img_view_thread = "bt_forum_abrir_tred.png";

    if(in_array($men->code,$this->show_open)) {
      $img_view = "bt_forum_fechar.png";
      $dsp = "block";
    }

    $msg_unique_name = "forum_message_".$men->code;
    $A_link = "<a href=\"javascript:Forum_toggleMessage('$msg_unique_name')\" class=\"forum_message_title\">";
    $new = false;

    if(($this->lastVisitTime>0) && !($ignore_new)) {
      if(($men->timePost>$this->lastVisitTime) && ($men->codeUser!=$_SESSION['user']->codeUser)) {
	$new = true;
	$ignore_new = true;
      }
    }

    $obj->add("<a name=\"$msg_unique_name\">");
    if($new) 
      $nc.= " forum_newMessage "; //new message class
    
    $obj->add("<div class=\"".$nc."forum_box\" id=\"super_$msg_unique_name\" >");
    $obj->add("<img src=\"".$img_arrow."\">$A_link<b>$men->title</b></a>");
    $obj->add("(");
    $obj->add(new AMTUserinfo($men->user->items[0],AMTUserinfo::LIST_USERNAME));
    $obj->add(",&nbsp;".date($_language['date_format'],$men->timePost).") &nbsp");
    
    $obj->add("$A_link <img id=\"img_$msg_unique_name\" src=\"$_CMAPP[images_url]/$img_view\"></a>");
    if($level==1) {
      $tmp_link = "<a href=\"javascript:Forum_toogleThread('$msg_unique_name')\" >";
      $obj->add("$tmp_link <img id=\"img_thread_$msg_unique_name\" src=\"$_CMAPP[images_url]/$img_view_thread\"></a>");
    }

    //message body  
    $obj->add("<div id=\"$msg_unique_name\" style=\"padding-top: 6px; position:relative; display:$dsp;\"><span id='body_$msg_unique_name'>".$men->body."</span>");
    //reply button
    $obj->add("<br><a href=\"#reply_$msg_unique_name\" onClick=\"Forum_displayReply('reply_$msg_unique_name',$men->code,'RE:$men->title')\"><img vspace=\"10\" hspace=\"12\" align=\"middle\" src=\"$_CMAPP[imlang_url]/forum_ico_responder.gif\" border=0></a>");

    //edit and delete buttons
    if(empty($men->children)) {
      $obj->add("<a href='#reply_$msg_unique_name' onClick=\"Forum_displayEdit('reply_$msg_unique_name',$men->code,'$men->title')\"> <img vspace=\"\10\" hspace=\"12\" align=\"middle\" src=\"$_CMAPP[imlang_url]/icon_editar.gif\" border=0></a>");
      $obj->add("<a href='#reply_$msg_unique_name' onClick=\"Forum_deleteMessage($men->code,$_REQUEST[frm_codeForum])\"> <img vspace=\"\10\" hspace=\"12\" align=\"middle\" src=\"$_CMAPP[imlang_url]/icon_excluir.gif\" border=0></a>");
    }


    $obj->add("</div>");


    $obj->add("<a name=\"anchor_reply_$msg_unique_name\"><div id=\"reply_$msg_unique_name\" class=\"box_forum_resposta\" style=\"position:relative; display:none;\"></div>");

    
    if(is_array($men->children)) {
      $obj->add("<br>");
      $level++;

      foreach($men->children as $child) {
	$obj->add($this->drawMessage($child,$theme,$level,$ignore_new));
      }
    }



    $obj->add("</div>");

    return $obj;
  }

  public function __toString() {
    global $_CMAPP,$_language;

    $imgOn = "$_CMAPP[images_url]/bt_forum_abrir.png";
    $imgOff = "$_CMAPP[images_url]/bt_forum_fechar.png";
    $imgAllOn = "$_CMAPP[images_url]/bt_forum_abrir_todos.png";
    $imgAllOff = "$_CMAPP[images_url]/bt_forum_fechar_todos.png";
    $imgThreadOn = "$_CMAPP[images_url]/bt_forum_abrir_tred.png";
    $imgThreadOff = "$_CMAPP[images_url]/bt_forum_fechar_tred.png";
    parent::addScript("Forum_preLoadImages('$imgOn','$imgOff','$imgAllOn','$imgAllOff','$imgThreadOn','$imgThreadOff');");
    parent::addScript("delete_url = '$_CMAPP[services_url]/forum/forum.php'; message_forum_delete = '$_language[delete_message]';");


    $campos_requisitados = array("title","body");
    $form = new AMWSmartForm('AMForumMessage', "cad_post", $_SERVER['PHP_SELF'],$campos_requisitados,array('parent','codeForum'));
    $form->cancel_button->setOnClick('Forum_cancelReply()');

    $form->addComponent("action", new CMWHidden("frm_action","A_post"));
    $form->components['codeForum']->setValue($_REQUEST['frm_codeForum']);
    $form->setLabelClass("titforumresposta");
    $form->setRichTextArea("body");
    $form->setDesign(CMWFormEl::WFORMEL_DESIGN_OVER);   // muda as labels do smart form

    parent::add("<div id=reference_div style='display: none'>");
    parent::add($form);
    parent::add("</div>");



    parent::add("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"98%\">");
    parent::add("<tr>");
    parent::add("<td bgcolor=\"#FAFBFB\" width=\"10\"><img src=\"$_CMAPP[images_url]/box_forum_01.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("<td bgcolor=\"#FBFAF9\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("<td bgcolor=\"#FAFBFB\" width=\"10\"><img src=\"$_CMAPP[images_url]/box_forum_02.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("</tr>");
    parent::add("<tr>");
    parent::add("<td bgcolor=\"#FAFBFB\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("<td bgcolor=\"#FAFBFB\">");
    parent::add("<table cellpadding=\"0\" cellspacing=\"0\" width=\"484\" background=\"$_CMAPP[images_url]/img_forum_bgtopic.gif\" border=\"0\">");

    parent::add("<tr>");
    parent::add("<td height=\"36\" width=\"45\" align=\"center\"><img src=\"$_CMAPP[images_url]/forum_ico_topicproj.gif\" width=\"26\" height=\"20\" border=\"0\"></td>");
    //nome do forum
    parent::add("<td class=\"forum_title\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"1\" height=\"12\" border=\"0\"><br>".$this->forum->name);
    
    parent::add("<td align=right>");
    parent::add("<a href=\"javascript:Forum_toogleAllMessages('$_CMAPP[services_url]/forum/handlevisualization.php')\"><img id=\"img_handle_all\" src=\"$_CMAPP[imlang_url]/bt_forum_abrir_todos.png\"></b>");
    parent::add("</td>");
    
    parent::add("</td>");
    
    //continua
    parent::add("</tr>");
    parent::add("</table></td>");
    parent::add("<td bgcolor=\"#FAFBFB\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("</tr>");

    parent::add("<tr bgcolor=\"#FAFBFB\"><td colspan=\"3\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"20\" height=\"20\" border=\"0\"></td></tr>");

    parent::add("<tr bgcolor=\"#FAFBFB\">");
    parent::add("<td><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");
    parent::add("<td valign=\"top\"><table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\">");


    $messages = $this->forum->listMessagesAsTree();
    $cont = 0;
    if(!empty($messages)) {
      parent::add("<div id=\"super_forum_messages\">");
      foreach($messages as $k=>$men) {
	//the rest of the divisio of $cont of the number of themes and the number of avaiable themes
	//will give us the actual theme mumber
	parent::add($this->drawMessage($men,$this->themes[($cont % $this->avaiable_themes)]));
	$cont++;
      }
      parent::add("</div>");

    }
    else {
      parent::add("<span class=\"forum_title\">$_language[no_messages]</span>");
    }
    parent::add("</table>");

    
    parent::add("<td bgcolor=\"#FAFBFB\"><img src=\"$_CMAPP[images_url]/dot.gif\" width=\"10\" height=\"10\" border=\"0\"></td>");

    parent::add("</table>");

    //new topic button
    parent::add("<div class=\"forum_novo_topico\">");
    parent::add("<a href=\"#anchor_reply_forum_new_topic\" onClick=\"displayReply('reply_forum_new_topic',0,'')\"><img src=\"$_CMAPP[imlang_url]/bt_forum_novo_topico.png\"></a>");
    parent::add("<a name=\"anchor_reply_forum_new_topic\"><div id=\"reply_forum_new_topic\" class=\"box_forum_resposta\" style=\"position:relative; display:none;\"></div>");
    
    parent::add("</div>");

    if(empty($messages)) {
      parent::addScript("Forum_displayReply('reply_forum_new_topic',0,'')");
    }

    if($_SESSION['amadis']['forum']['visualization']=="open") {
      parent::addPageEnd(CMHTMLObj::getScript("Forum_openAllMessages()"));
    }
    return parent::__toString();
  }


}

?>