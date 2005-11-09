var alertClear = new Array();
var EnvSession_numFriendsInvitation;
var EnvSession_timeoutHandler;

function EnvSession_timeout(param) {
  EnvSession_timeoutHandler = window.setTimeout(param,15000);
}

function clearAlerts(timeout) {
  if(timeout == "timeout") {
    EnvSession_timeout("clearAlerts('clear');");
    return;
  }

  var divF = AM_getElement("friends_invitation");
  if(EnvSession_numFriendsInvitation == 0) {
    divF.parentNode.removeChild(divF);
  }
  if(timeout == "clear") {
    window.clearTimeout(EnvSession_timeoutHandler);
    return;
  }

}


var AMEnvSessionCallBack = {
  changemenustatus: function() { },
  changemode: function() { },
  makefriend: function(result) {
    var div = AM_getElement(result.divId);
    var node = window.document.createElement("DIV");
    node.innerHTML = result.msg;
    
    div.parentNode.replaceChild(node, div);
    
    EnvSession_numFriendsInvitation--;
    clearAlerts("timeout");
  },
  rejectfriend: function(result) {
    var div = AM_getElement(result.divId);
    div.parentNode.removeChild(div);

    EnvSession_numFriendsInvitation--;
    clearAlerts("clear");

  }
}


function changeMenuStatus(menu,menu_name) {
  var status = eval(menu_name+'_open');
  
  AMEnvSession.changemenustatus(menu, status);
  
  //dcomSendRequest(handler+'?frm_menu='+menu+'&frm_status='+status);
}

var EnvSession_finderRequestTimeout, finderAlert;
var openChat = new Array();


function initEnvironment() {

  finderAlert = window.document.createElement("DIV");
  window.document.appendChild(finderAlert);
  EnvSession_finderRequestTimeout = window.setTimeout("getFinderRequest(EnvSession_codeUser)",5000);

}

function getFinderRequest(codeUser) {
  
  
  
}