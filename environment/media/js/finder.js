var AMFinder_Timeout;
var AMFinder_lang = new Array();

function Finder_initChat(id) {
  var cssMessages = window.document.createElement("LINK");
  cssMessages.setAttribute("rel", "stylesheet");
  cssMessages.setAttribute("href", "css/mensagens.css");

  var chatFrame = AM_getElement("chat_"+id);
  var chatDoc = AM_getIFrameDocument(chatFrame, "body");
    
  var head = chatDoc.getElementsByTagName("head");
  head[0].appendChild(cssMessages);
  
}

var AMFinderCallBack = {
  onAddChat: function (result) {
    if(result.success == 1) {
      var tab = document.createElement("SPAN");
      tab.setAttribute('class', 'active_finder');
      tab.setAttribute('id', 'Tab_'+result.sessionId);
      tab.setAttribute('onClick', "Finder_toggleChatTab('"+result.sessionId+"');");
      tab.innerHTML = result.username;
      ChatTabs.appendChild(tab);

      var div = document.createElement("DIV");
      div.setAttribute("id", "ChatTab_"+result.sessionId);
      div.innerHTML = result.box;
      div.style.display = 'block';
      if(Finder_show != null) AM_togleDivDisplay(Finder_show);
      
      ChatContainer.appendChild(div);
      Finder_show = div.id;
    }
  },
  onGetOnlineUsers: function(result) {
    if(result != 0) {
      for(var i in result.data) {
	if(isNaN(i)) continue;
	status = (result.data[i]['flagEnded']=="FALSE" ? result.data[i]['visibility'] : "offline");
	Finder_changeUserStatus("UserIco_"+i, result.src, status);
      }
    }
  },
  onGetNewMessages: function(result) {

    var msg = window.document.createElement("DIV");
    msg.setAttribute("id","messagesBox");
    var chatDoc = AM_getElement("iChat_"+result.sessionId);
	
    for(var i in result) {
      switch(result[i].responseType) {
      case "finder_alert":
	  	var iChat = AM_getIFrameDocument(chatDoc);
		msg.innerHTML = "<br><div style='font-color: #FF0000'>"+result[i].message+"</div>";
		iChatDoc.body.appendChild(msg);
		//chatDoc.appendChild(msg);
		chatDoc.scrollTop += chatDoc.scrollHeight;
		break;

      case "finder_timeout":
	  	var iChat = AM_getIFrameDocument(chatDoc);
		//msg.innerHTML = "<br><span class='finder_timeout'>"+AMFinder_lang[result[i].message]+"</span>";
		msg.innerHTML = "<br><div class='finder_timeout'>"+AMFinder_lang[result[i].message]+"</div>";
		window.clearInterval(eval("AMFinder_Timeout_"+result.sessionId));
		iChat.body.appendChild(msg);
		chatDoc.scrollTop += chatDoc.scrollHeight;
		Finder_tabAlert(result.sessionId, 'offline_finder', true);
		break;

      case "parse_messages":
		var iChat = AM_getIFrameDocument(chatDoc);
		 
		Finder_tabAlert(result.sessionId, 'alert_finder', false);
		var out = "";
		out += "<br><div class='"+result[i].style+"'>";
		out += result[i].username+"("+result[i].date+"): ";
		out += result[i].message;
		out += "</div>";
	
		msg.innerHTML += out;
		
		iChat.body.appendChild(msg);
		chatDoc.contentWindow.scrollBy(0,1000);
		break;
      }
    }
  }
}

function Finder_openChatWindow(sessionId) {

  if(Finder_window != null) {
    Finder_window.Finder_loadTab(sessionId);
    Finder_window.focus();
    return false;
  } else {
    var userIds = sessionId.split("_");
    var param = "resizable=no,width=676,height=442,status=no,location=no,scrollig=yes,toolbar=no,scrollbars=yes";
    var param = '';
	Finder_window = window.open(Finder_chatSRC+"?frm_codeUser="+userIds[1], "Finder_ChatRoom", param);
  }
}

function Finder_closeFinder(id) {
  AMFinder.closeFinderChat(id);
}


function Finder_changeUserStatus(id, src, status) {
  var userIco = AM_getElement(id);

  imgSRC = src;
  
  switch (status) {
  case "VISIBLE":
    userIco.src = imgSRC+"/ico_user_on_line.png";
    userIco.addEventListener('click',Finder_openChatWindow,false);
    return(false);
    break;
  case "BUSY" :
    userIco.src = imgSRC+"/ico_user_ocupado.png";
    userIco.addEventListener('click',Finder_openChatWindow,false);
    return(false);
    break;
  case "HIDDEN" :
    userIco.src = imgSRC+"/ico_user_off_line.png";
    userIco.addEventListener('click',Finder_openChatWindow,false);
    return(false);
    break;
  default:
    userIco.src = imgSRC+"/ico_user_off_line.png";
    userIco.removeEventListener('click',Finder_openChatWindow,false);
    return(false);
    break;
  }

}

var contAlert = new Array();

function Finder_alertUser(userId, src, codeMessage) {

  box = AM_getElement("finderAlert");

  if(contAlert[userId] != 1) {
    userInfo = document.createElement("DIV");
    userInfo.id = "finderAlert_"+userId;
    
    src += "/finder/findertip.php?frm_codeUser="+parseInt(userId)+"&frm_codeMessage="+codeMessage;
        
    IFFinderAlert  = "<iframe name='IFFinderAlert"+userId+"' name='IFFinderAlert"+userId+"' src='"+src;
    //IFFinderAlert += "' frameborder=1></iframe>";
    IFFinderAlert += "'  width='141' height='154' frameborder='0'></iframe>";
    
    userInfo.innerHTML = IFFinderAlert;
    box.appendChild(userInfo);
    
    contAlert[userId] = 1;
  }
  
}


function Finder_removeAlert(userId, src) {
  box = AM_getElementIn("finderAlert", parent.document);
  for(var i in box.childNodes) {
    if(box.childNodes[i].nodeName == "DIV" && box.childNodes[i].id == "finderAlert_"+userId) {
      box.removeChild(box.childNodes[i]);
    }
  }
  contAlert[userId] = 0;
  (src != null)? dcomSendRequest(src):null
}


function Finder_clearChatBox() {
  box = AM_getElement("frm_message");
  box.value=null;
  box.focus();
}

function restoreConnection() {
  if(AMFinder_Timeout==null) {
    AMFinder.updateTimeOut(senderId+"_"+recipientId,'',function(result){});
    AMFinder_Timeout = window.setInterval("Finder_getNewMessages();", 5000);
  }
}

function Finder_getNewMessages(sessionId) {

  try {
    AMFinder.getNewMessages(sessionId, AMFinderCallBack.onGetNewMessages);
  }catch(Exception) {
    alert(Exception.message);
  }
}

var Finder_show=null;;
function Finder_tabAlert(sessionId, style, CForce) {
  if(Finder_show == "ChatTab_"+sessionId && CForce==false) return false;
  var tab = AM_getElement("Tab_"+sessionId);
  tab.setAttribute('class', style);
}

function Finder_toggleChatTab(sessionId) {
  var id = "ChatTab_"+sessionId;
  if(Finder_show == id) return false;
  AM_togleDivDisplay(Finder_show);
  AM_togleDivDisplay(id);
  
  var tab = AM_getElement('Tab_'+sessionId);
  if(tab.getAttribute('class') == "alert_finder") tab.setAttribute('class','active_finder');

  Finder_show = id;
}


function Finder_conversation(sessionId) {
  this.sessionId = sessionId;
  this.closeFinder = Finder_closeFinder(sessionId);
  this.chatTab = "ChatTab_"+sessionId;
  this.getNewMessages = AMFinder.getNewMessages;
  this.httpRequest = eval("AMFinder_"+sessionId);
  alert(this.httpRequest);
  Finder_register(this);
}


Finder_conversations = new Array();
function Finder_register(obj) {
  Finder_conversations[obj.sessionId] = obj;
  Finder_loadTab(obj.sessionId);
}

function Finder_loadTab(id) {
  AMFinder.addChat(id, AMFinderCallBack.onAddChat);
}
