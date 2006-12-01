var timeTable = new Array();
timeTable['finder'] = 60000;
timeTable['chat'] = 5000;

var alertClear = new Array();
var EnvSession_numFriendsInvitation;
var EnvSession_timeoutHandler;
var Finder_window = null;;

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
  onMakeFriend: function(result) {
    var div = AM_getElement(result.divId);
    var node = window.document.createElement("DIV");
    node.innerHTML = result.msg;

    div.parentNode.replaceChild(node, div);
    
    EnvSession_numFriendsInvitation--;
    clearAlerts("timeout");
  },
  onRejectFriend: function(result) {
    var div = AM_getElement(result.divId);
    div.parentNode.removeChild(div);

    EnvSession_numFriendsInvitation--;
    clearAlerts("clear");

  },
  onGetFinderRequest: function(result) {
    if(result != 0) {
      var top = 0;
      var box = AM_getElement("finderAlert");
      
      for(var i in result) {
	if(result[i].id != undefined) {
	  var userInfo = document.createElement("DIV");
	  userInfo.id = "finderAlert_"+result[i].id;
	  //alert(result[i].id);
	  userInfo.innerHTML = result[i].tip;
	  
	  userInfo.style.setProperty("display", "table", "");
	  
	  var exists = AM_getElement("finderAlert_"+result[i].id);
	  
	  if(exists != null) {
	    box.insertBefore(userInfo, exists);
	    box.removeChild(exists);
	    //box.replaceChild(userInfo, exists);
	  } else {
	    box.appendChild(userInfo);
	  }
	}
	//alert(box.innerHTML);
      }//end for
    }
  }//end getfinderrequest
}

function changeMenuStatus(menu,menu_name) {
  var status = eval(menu_name+'_open');
  
  AMEnvSession.onChangeMenuStatusError=AM_callBack.onError;
  AMEnvSession.changeMenuStatus(menu,status);
}

var EnvSession_finderRequestTimeout, finderAlert;
var openChat = new Array();


function initEnvironment() {

  finderAlert = window.document.createElement("DIV");
  finderAlert.setAttribute("id","finderAlert");

  window.document.body.appendChild(finderAlert);
  
  AMFinder.getOnlineUsers(AMEnvSessionCallBack.onGetOnlineUsers);
  AMEnvSession.getFinderRequest(AMEnvSessionCallBack.onGetFinderRequest);
}

function getFinderRequest() {
  AMEnvSession.onGetFinderRequestError = function (result) {
    alert(result);
  };
  try {
    AMEnvSession.getFinderRequest(AMEnvSessionCallBack.onGetFinderRequest);
  }catch(Exception) {
    AM_callBack.onError(Exception);
  }
}

function Finder_removeAlert(id) {
  box = AM_getElementIn(id);
  box.parentNode.removeChild(box);
}

