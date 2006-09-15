
var userinfo_url = ""
var loading_message = "Loading userinfo data";


var AMTUserinfoRenderCallBack = {

  onRender: function(result) {
    div = AM_getElement("toolTipSpan");
    div.innerHTML = result;

    return;
  }
}


//function amuserinfo(user,name,email,dtnas,media) {
function amuserinfo(user) { 
  AMTUserinfoRender.onRenderError = AM_callBack.onError;
  AMTUserinfoRender.render(user, AMTUserinfoRenderCallBack.onRender);
  return loading_message;
}

function initAMUserinfo() {
  var ret="";


}