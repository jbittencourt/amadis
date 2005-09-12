
var userinfo_url = ""
var loading_message = "Loading userinfo data";

function switchUserinfoHTML() {
  var _y;
  
  _y = AM_getElement("toolTipSpan");
  frame = AM_getElement("userinfo_iframe");

  AM_loadIframeIntoDiv(frame,_y);
  
  return true;
}

//function amuserinfo(user,name,email,dtnas,media) {
function amuserinfo(user) { 
  var ret = "";
  ret+= loading_message;

  frame = getBlockElement("userinfo_iframe");
  frame.src = userinfo_url+user;

  return ret
}

function initAMUserinfo() {
  var ret="";

  ret+= "<IFRAME style=\"display:none \" id=\"userinfo_iframe\"  onLoad=\"switchUserinfoHTML()\" scrolling=no frameborder=0 \">";
  ret+= "Could not load data.";
  ret+= "</IFRAME>";
  document.write(ret);

}