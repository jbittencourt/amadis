
function Finder_initChat() {
  var cssMessages = window.document.createElement("LINK");
  cssMessages.setAttribute("rel", "stylesheet");
  cssMessages.setAttribute("href", "css/mensagens.css");

  var chatFrame = AM_getElement("chat");
  var chatDoc = AM_getIFrameDocument(chatFrame, "body");
    
  var head = chatDoc.getElementsByTagName("head");
  head[0].appendChild(cssMessages);
  
}

var AMFinderCallBack = {
  getonlineusers: function(result) {
    if(result != 0) {
      for(var i in result.data) {
	if(isNaN(i)) continue;
	status = (result.data[i]['flagEnded']=="FALSE" ? result.data[i]['visibility'] : "offline");
	Finder_changeUserStatus("UserIco_"+i, result.src, status);
      }
    }
  },
  closefinderchat: function(result) { },
  getnewmessages: function(result) {
    
    var chatFrame = AM_getElement("chat");
    var chatDoc = AM_getIFrameDocument(chatFrame, "body");

    var msg = window.document.createElement("DIV");
    msg.setAttribute("id","messagesBox");

    for(var i in result) {
      switch(result[i].responseType) {
      case "finder_alert":
	//chatFrame.innerHTML += "<br><span style=''>"+AMFinder_lang[result[i].message]+"</span>";
	msg.innerHTML = "<br><div style='font-color: #FF0000'>"+result[i].message+"</div>";
	chatDoc.body.appendChild(msg);
	break;

      case "finder_timeout":
	//chatFrame.innerHTML += "<br><span style=''>"+AMFinder_lang[result[i].message]+"</span>";
	msg.innerHTML = "<br><div class='finder_timeout'>"+result[i].message+"</div>";
	//window.clearTimeout(AMFinder_timeOut);
	chatDoc.body.appendChild(msg);
	break;

      case "parse_messages":
	var out = "";
	out += "<br><div class='"+result[i].style+"'>";
	out += result[i].username+"("+result[i].date+"): ";
	out += result[i].message;
	out += "</div>";

	msg.innerHTML = out;
	chatDoc.body.appendChild(msg);    
	break;
      }
    }
    
  }
  
}

// function Finder_openChatWindow(src,userId) {
//   if(finderWindows[userId] == null) {
//     var param = "resizable=no,width=590,height=435,status=no,location=no,scrollig=yes,toolbar=no,scrollbars=yes";
//     finderWindows[userId] = window.open(src, "Finder_"+userId, param);
//   }else finderWindows[userId].focus();
// }


function Finder_openChatWindow(e) {
  var pos = this.id.lastIndexOf("_");
  var userId = this.id.substring((pos+1),this.id.length);

  if(ajaxSync.syncTableObjects['finderRoom_'+userId] != null) {
    ajaxSync.syncTableObjects['finderRoom_'+userId][0].focus();
  }else {
    var param = "resizable=no,width=676,height=442,status=no,location=no,scrollig=yes,toolbar=no,scrollbars=yes";
    var w = window.open(Finder_chatSRC+"?frm_codeUser="+userId, "finderRoom_"+userId, param);
    ajaxSync.register(w, 'Finder_getNewMessages', 'finderRoom_'+userId, 'chat');//Finder_getNewMessages
    //ajaxSync.syncTableObjects['finderRoom_17'][0].alert('asdfsf');
  }
}

function Finder_closeFinder(id) {
  ajaxSync.unlink(id);
  AMFinder.closefinderchat(id);
}


function Finder_initFinder(handler) {
  string="Finder_loadFinder('"+handler+"');";
  id = setTimeout(string, 2000);
}

function Finder_loadFinder(handler) {
  dcomSendRequest(handler);
  clearTimeout(id);
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


function Finder_changeStatus(element, src) {
  //debugBrowserObject(element);
  //var handle = src+"/finder/finder.php?action=A_change_mode&amp;frm_mode="+element.value;
  //dcomSendRequest(handle);
  //AMFinder.chagemode(element.value);
}


function Finder_clearChatBox() {
  box = AM_getElement("frm_message");
  box.value=null;
  box.focus();
}

function Finder_sendMessage(form) {

  
  var recipient = form.elements['frm_codeRecipient'].value;
  var msg = form.elements['frm_message'].value;
  
  AMFinder.sendmessage(recipient, msg);
  
}

function Finder_getNewMessages() {
  AMFinder.getnewmessages(senderId, recipientId);
}

var reSynctimeout;
function reSync() {
  alert("resyncing");
  reSynctimeout = window.setTimeout('register();',5000);
}

function register() {
  window.opener.ajaxSync.register(window, 'Finder_getNewMessages', window.name, 'chat');
  window.clearTimeout(reSynctimeout);
}
