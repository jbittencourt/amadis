var finderWindows = new Array();
var AMFinder_timeOut;

var AMFinderCallBack = {
  gettimeout: function(result) {
    alert('<p>'+result+'</p>');
  },
  sendmessage: function(result) {
    Finder_clearChatBox();
  },
  getnewmessages: function(result) {
    var chatFrame = AM_getElement("chat");
    for(var i in result) {
      switch(result[i].responseType) {
      case "finder_alert":
	//chatFrame.innerHTML += "<br><span style=''>"+AMFinder_lang[result[i].message]+"</span>";
	chatFrame.innerHTML += "<br><span style='font-color: #FF0000'>"+result[i].message+"</span>";
	break;

      case "finder_timeout":
	//chatFrame.innerHTML += "<br><span style=''>"+AMFinder_lang[result[i].message]+"</span>";
	chatFrame.innerHTML += "<br><span class='finder_timeout'>"+result[i].message+"</span>";
	//window.clearTimeout(AMFinder_timeOut);
	break;

      case "parse_messages":
	var out = "";
	out += "<br><span class='"+result[i].style+"'>";
	out += result[i].username+"("+result[i].date+"): ";
	out += result[i].message;
	out += "</span>";

	chatFrame.innerHTML += out;
	    
// 	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr>\n";
// 	echo "<td width=\"10%\" class=\"$class\"><font color=\"$color\">".$message->users[0]->username."</font>";
// 	echo "<font size=-1>($hora)</font></td><td class=\"$class\">$message->message</td>\n";
// 	echo "</table>\n";

	break;
      }
    }
  }
  
}

function Finder_openChatWindow(src,userId) {
  if(finderWindows[userId] == null) {
    var param = "resizable=no,width=590,height=435,status=no,location=no,scrollig=yes,toolbar=no,scrollbars=yes";
    finderWindows[userId] = window.open(src, "Finder_"+userId, param);
  }else finderWindows[userId].focus();
}


function AFinder_openChatWindow(e) {
  var pos = this.id.lastIndexOf("_");
  var userId = this.id.substring((pos+1),this.id.length);

  if(finderWindows[userId] == null) {

    var src = Finder_chatSRC+"?frm_codeUser="+userId;
    
    var param = "resizable=no,width=590,height=435,status=no,location=no,scrollig=yes,toolbar=no,scrollbars=yes";
    finderWindows[userId] = window.open(src, "Finder_"+userId, param);
  }else {
    finderWindows[userId].focus();
  }
}

function Finder_closeFinder(id) {
  //finderWindows[id].close();
  finderWindows[id] = null;
  handler = finder_url+"?action=A_close_chat&frm_codeUser="+id;
  dcomSendRequest(handler);
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
  
  imgSRC = src+"/media/images";
  
  switch (status) {
  case "online":
    userIco.src = imgSRC+"/ico_user_on_line.png";
    userIco.addEventListener('click',AFinder_openChatWindow,false);
    return(false);
  case "offline":
    userIco.src = imgSRC+"/ico_user_off_line.png";
    userIco.removeEventListener('click',AFinder_openChatWindow,false);
    return(false);
  case "busy" :
    userIco.src = imgSRC+"/ico_user_ocupado.png";
    userIco.addEventListener('click',AFinder_openChatWindow,false);
    return(false);
  case "hidden" :
    userIco.src = imgSRC+"/ico_user_off_line.png";
    userIco.addEventListener('click',AFinder_openChatWindow,false);
    return(false);
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



