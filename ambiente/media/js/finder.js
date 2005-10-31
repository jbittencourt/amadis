var finderWindows = new Array();

var AMFinderCallBack = {
  gettimeout: function(result) {
    alert('<p>'+result+'</p>');
  },
  getmodes: function(result) {
    alert(result);
  },
  changemode: function(result) { alert(result); }
}

function Finder_openChatWindow(src,userId) {
  if(finderWindows[userId] == null) {
    var param = "resizable=no,width=590,height=435,status=no,location=no,scrollig=yes,toolbar=no,scrollbars=yes";
    finderWindows[userId] = window.open(src+"?frm_codeUser="+userId, "Finder_"+userId, param);
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
  var frame = AM_getElement('IFSendMessage');
  alert(recipient);
  var url = finder_url+"?action=A_send_message&frm_codeRecipient="+recipient+"&frm_message="+msg;
  
  frame.src = url;

}



