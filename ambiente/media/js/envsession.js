var timeTable = new Array();
timeTable['finder'] = 60000;
timeTable['chat'] = 10000;

var ajaxSync = {
  syncTableObjects:new Array(),
  syncTable:new Array(),
  lock:false,
  interval:"",
  sync:function() {
    this.syncTable = new Array();
    var overtime=0;
    for(var i in this.syncTableObjects) {
      var obj = this.syncTableObjects[i];
      var stringFunction = "ajaxSync.syncTableObjects['"+i+"'][0][ajaxSync.syncTableObjects['"+i+"'][1]]();";
      this.syncTable[i] = window.setInterval(stringFunction, timeTable[obj[2]]+overtime);
      overtime += 5000;
      alert(overtime);
    }
  },
  register:function(obj, functionName, name, time) {
    this.syncTableObjects[name] = new Array(obj, functionName, time);
    this.clear();
    this.sync();
  },
  clear:function() {
    if(this.syncTable.length > 0) {
      for(var i in this.syncTable) {
	window.clearInterval(this.syncTable[i]);
      }
    }
  },
  unlink:function(name) {
    window.clearInterval(this.syncTable[name]);
    delete(this.syncTableObjects[name]);
  },
  send:function() {
    for(var i in this.syncTableObjects) {
      this.syncTableObjects[i][0].reSync();
    }
  },
  kill:function() {
    for(var i in this.syncTableObjects) {
      if(typeof(this.syncTableObjects[i]) == 'window') {
	this.syncTableObjects[i].close();
      }else this.syncTableObjects[i] = null;
    }
  }
};

/**
 *Esta eh uma funcao abstrata que deve ser implementada no seu cliente de chat.
 *Ela eh chamada pelo metodo ajaxSync::send(), isso serve para que nao tenhamos 
 *colisoes no tunel de comunicacao do JPSpan AJAX Framework.
 */

// abstract var register = function(){};

/**
 *Esta eh uma funcao abstrata que deve ser implementada no seu cliente de chat.
 *Ela eh chamada pelo metodo ajaxSync::send(), isso serve para que nao tenhamos 
 *colisoes no tunel de comunicacao do JPSpan AJAX Framework.
 */

// abstract var reSync = function(){};

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

  },
  getfinderrequest: function(result) {

    if(result != 0) {
      var top = 0;
      var box = AM_getElement("finderAlert");
      
      for(var i in result) {
	if(result[i].id != undefined) {
	  var userInfo = document.createElement("DIV");
	  userInfo.id = "finderAlert_"+result[i].id;
	  
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
  
  AMEnvSession.changemenustatus(menu, status);
  
}

var EnvSession_finderRequestTimeout, finderAlert;
var openChat = new Array();


function initEnvironment() {

  finderAlert = window.document.createElement("DIV");
  finderAlert.setAttribute("id","finderAlert");

  window.document.body.appendChild(finderAlert);

  AMFinder.getonlineusers();
  AMFinder.reSync = function(){};
  ajaxSync.register(AMFinder, "getonlineusers", "finderTimeOut", "finder");
  ajaxSync.register(AMEnvSession, "getfinderrequest", "finderRequest", "finder");

  //var w = window.open('http://www.yahoo.com','win2');
  //ajaxSync.register(w, "focus", 'win2', 'chat');
  //ajaxSync.register(AMEnvSession, "getfinderrequest", "finderTimeOut", 'finder');
  //ajaxSync.register(AMEnvSession, "getfinderrequest", "finderTime");
  //EnvSession_finderRequestTimeout = window.setInterval("getFinderRequest();",10000);
  
}

function getFinderRequest() {
 
  AMEnvSession.getfinderrequest();
  
}

function Finder_removeAlert(id) {
  box = AM_getElementIn(id);
  box.parentNode.removeChild(box);
}

