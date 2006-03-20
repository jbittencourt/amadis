var Chat_alertColor  = "#FF0000";
var Chat_originalColor = "#E1F7F9";
var createProgress = false;
var Chat_alerts = new Array();
var createChatTimeout;

var AMChatCallBack = {
  verifynameexists: function(result) {
    if(result == 1) {
      window.clearTimeout(createChatTimeout);
      chkName = false;
      var name = AM_getElement('frm_room_name');
      var msg = window.document.createElement("SPAN");
      msg.innerHTML = "*Este nome j&aacute; est&aacute; sendo utilizado!<br>";
      msg.style.setProperty("color", Chat_originalColor,"");
      msg.setAttribute("id", "alertRoomName");
      
      changeBgColor(name.parentNode, Chat_alertColor);
      name.parentNode.insertBefore(msg, name);
      name.focus();
    }
  },
  createchatroom: function(result) {
    window.clearTimeout(createChatTimeout);
    
    var table = AM_getElement("create_chat_box");
    
    var alertBox = window.document.createElement("DIV");
    alertBox.setAttribute("id", "create_chat_alert");
    alertBox.innerHTML = result.msg;
    
    table.parentNode.insertBefore(alertBox, table);

    var chat = window.document.createElement("DIV");
    chat.setAttribute("id","room_"+result.obj.code);
    
    switch(result.error) {
    case "saved":
      //alert(result.tmp);
      if(result.type == 'no_scheduled') {
	var openedChats = AM_getElement("openedChatRooms");

	chat.innerHTML  = "<img width='31' height='15' border='0' src='"+cmapp_images_url+"/bt_chat_balao.gif'>&nbsp;";
	chat.innerHTML += "<a href='#' onClick=\"Chat_openChat("+result.obj.code+", 'chat','"+Chat_room_url+"');\" class='linkchat'><b>"+result.obj.name+"</b></a>";
	
	var no_chats = AM_getElement("no_opened_chats");
	if(no_chats == null) openedChats.insertBefore(chat, openedChats.firstChild);
	else openedChats.replaceChild(chat, no_chats);
	
      } else if(result.type == 'scheduled') {
	var scheduledChats = AM_getElement("scheduledChatRooms");
	var date = result.obj.init;

	chat.setAttribute("class","textoverde");

	var out = "<b>"+result.obj.name+"</b><br>";
	out += language_scheduled_to+"&nbsp;<span class='datachat'>"+date.hours+":"+date.minutes+"&nbsp;"+language_of_day+"&nbsp;"+date.mday+"/"+date.mon+"/"+date.year+"</span>";
	
	chat.innerHTML = out;
	
	var no_chats = AM_getElement("no_scheduled");
	if(no_chats == null) scheduledChats.insertBefore(chat, scheduledChats.firstChild);
	else scheduledChats.replaceChild(chat, no_chats);

      }
      
      break;
    }
  },
  getnewmessages:function(result) {
    var chatBox = AM_getElement("chatBox");
    if(result != 0) {
      for(var i=0; i < result.length; i++) {
	var msg = result[i].replace("{ALL}",language_all);
	chatBox.innerHTML += msg.replace("{TALKTO}",language_talk_to);
	chatBox.scrollTop += 200;
      }
    }
  },
  leaveroom:function() {}
}

var chkSubject=false;
var chkName=false;
function Chat_saveChat(form) {
  
  var name = form.elements['frm_room_name'];
  var subject = form.elements['frm_room_subject'];
  var beginDate = form.elements['frm_beginDate'];
  var endDate = form.elements['frm_endDate'];
  var infinity = form.elements['frm_infinity'];
  var code = form.elements['frm_code'];
  var type = form.elements['frm_type'];

  if(name.value.length != 0) {
    AMChat.verifynameexists(name.value);
  } else if(!chkName){
    chkName = true;
    var msg = window.document.createElement("SPAN");
    msg.innerHTML = "*Preencha com o nome para sala!<br>";
    msg.style.setProperty("color", Chat_originalColor,"");
    name.focus();
    //original color e1f7f9 
    changeBgColor(name.parentNode, Chat_alertColor);
    name.parentNode.insertBefore(msg, name);
  }
  
  if(subject.value.length == 0 && !chkSubject) {
    chkSubject = true;
    
    var msg = window.document.createElement("SPAN");
    msg.innerHTML = "*Preencha com o assunto da sala!<br>";
    msg.style.setProperty("color", Chat_originalColor,"");
    subject.focus();
    
    changeBgColor(subject.parentNode, Chat_alertColor);
    subject.parentNode.insertBefore(msg, subject);
    return false;
  }
  
  if(checkDate) {
    if(validatefrm_beginDate()) {
      if(infinity.checked == false) {
	if(validatefrm_endDate()) {
	  if(parseInt(endDate.value) < parseInt(beginDate.value)) {
	    alert("A data de inicio deve ser menor que a data de fim do chat");
	  }
	} else alert("*Por favor cheque os dados!");
      } else endDate.value=0;
    } else return false;
  } else {
    beginDate.value = 0;
    endDate.value = 0;
  }

  createChatTimeout = window.setTimeout(function() {
    AMChat.createchatroom(name.value, subject.value, beginDate.value, endDate.value, infinity.checked, type,
			  language_save_chat_success, language_save_chat_error, code.value);
  }
					, 2000);

  return false;
}

function changeBgColor(obj, color) {
  var att = obj.getAttribute("bgColor");
  if(att != null) 
    obj.removeAttribute("bgColor");
  
  obj.setAttribute("bgColor", color);
  
}

function Chat_clearAlert(id) {
  var obj = AM_getElement(id);
  changeBgColor(obj.parentNode, Chat_originalColor);
  
  if(obj.previousSibling.nodeName == "SPAN")
    obj.parentNode.removeChild(obj.previousSibling);

  var alertBox = AM_getElement("create_chat_alert");
  if(alertBox != null) {
    alertBox.parentNode.removeChild(alert);
  }
}

function Chat_openChat(codeRoom, type, url) {
  if(ajaxSync.syncTableObjects['chatRoom_'+codeRoom] != null) {
    ajaxSync.syncTableObjects['chatRoom_'+codeRoom][0].focus();
  }else {
    var w = window.open(url+"?frm_codeRoom="+codeRoom, "chatRoom_"+codeRoom, "width=540, height=620, resizable=false");
    ajaxSync.register(w, 'Chat_getNewMessages', 'chatRoom_'+codeRoom, 'chat');
  }
}

function Chat_closeChat() {
  AMChat.leaveroom(Chat_codeRoom, Chat_codeConnection, language_exit_room);
  window.opener.ajaxSync.unlink(window.name);
  window.close();
}

function Chat_getNewMessages() {
  AMChat.getnewmessages(Chat_codeRoom);
}

var reSynctimeout;
function reSync() {
  alert("resyncing");
  reSynctimeout = window.setTimeout('register();',5000);
}

function register() {
  window.opener.ajaxSync.register(window, 'Chat_getNewMessages', window.name, 'chat');
  window.clearTimeout(reSynctimeout);
}

