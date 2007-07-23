is_dom = (document.getElementById) ? true : false;
var is_ns5 = ((navigator.userAgent.indexOf("Gecko")>-1) && is_dom) ? true: false;
var is_ie5 = ((navigator.userAgent.indexOf("MSIE")>-1) && is_dom) ? true : false;
var is_ns4 = (document.layers && !is_dom) ? true : false;
var is_ie =  (document.all) ? true: false;
var is_ie4 = (document.all && !is_dom) ? true : false;
var is_nodyn = (!is_ns5 && !is_ns4 && !is_ie4 && !is_ie5) ? true : false;



//AMADIS global javascript variables. This variables are inicialized in the
//AMMain class

var CMAPP = new Array();
CMAPP['url']   = null;
CMAPP['media_url']  = null;
CMAPP['images_url'] = null;
CMAPP['imlang_url'] = null;
CMAPP['js_url']     = null;
CMAPP['css_url']    = null;
CMAPP['services_url']  = null;
CMAPP['pages_url']  = null;
CMAPP['thumbs_url']  = null;


//Start of the functions

function AM_getElement(id, doc) {
  if(doc == null) doc = document;

  if(is_ie4) {
    return doc.all(id);
  }
  else {  
    if(is_dom) {
      return doc.getElementById(id);
    }
    else {
      return null;
    }
  }
}


function AM_getElementIn(id,doc) {
  return AM_getElement(id,doc);
}


function AM_getIFrameDocument(objFrame,name) {
  var objDoc = (objFrame.contentDocument) ? objFrame.contentDocument //IE5.5+, Moz 1.0+, Opera
               : (objFrame.contentWindow) ? objFrame.contentWindow.document
               : (window.frames && window.frames[name]) ? window.frames[name].document //IE5, Konq, Safari
               : (objFrame.document) ? objFrame.document 
               : null;

  return objDoc
}


function AM_togleDivDisplay(id) {
  var div = AM_getElement(id);

  if(div.style.display==null) {
    div.style.display="block";
    return "opened";
  }
  if(div.style.display=="block") {
    div.style.display="none";
    return "closed"
  }
  else {
    div.style.display="block";
    return "opened";
  }
}


function AM_hiddeDiv(id) {
  var div = AM_getElement(id);
  div.style.display="none";
}

function AM_showDiv(id) {
  var div = AM_getElement(id);
  div.style.display="block";
}


function AM_loadIframeIntoDiv(iframe,div) {
  if((div==null) || (iframe==null)) return false;
  div.innerHTML = AM_getIFrameDocument(iframe).body.innerHTML;
  return true;
}

function AM_debugBrowserObject(obj) {
  var outPut;
  //outPut  = "<table cellspacing=0 cellpadding=0 border=1>";
  //outPut += "<tr><td><b>Property</b></td><td><b>Type</b></td></tr>";
  for (var i in obj) {
    var property = obj[i];
    out += i+"###";
    out += property+"<br />";
   
  }
  //out += "</table>";
  document.write(out);
  debug = document.createElement("DIV");
  debug.innerHTML = out;
  document.body.appendChild(debug);
}

function AM_changeImage(id,varImg) {
  img = AM_getElement(id);
  img.src = varImg.src;
}

var AM_editorButtons = [];

function AM_registerEditorButtons(button) {
  
  if(typeof button != "object") {
    alert("AM_registerEditorButtons::buttom deve ser um object {}");
    return 0;
  }
  AM_editorButtons.push(button);
}

function AM_getRegisteredEditorButtons() {
  return AM_editorButtons;
}

var AM_editorInitAction = [];
function AM_registerEditorInitActions(action) {
  AM_editorInitAction.push(action);
}

function AM_getRegisteredEditorInitActions() {
  return AM_editorInitAction;
}

var AM_handlers = new Array();
function AM_registerHandlerId(handlerId) {
  AM_handlers[handlerId] = handlerId;
}

function AM_unregisterHandlerId(handlerId) {
  AM_handlers[handlerId] = null;
}

function AM_openURL(url) {
  window.location = url;
}


function AM_addCSSMessage() {
  return '<link rel="stylesheet" type="text/css" href="../media/css/alertbox.css">';
}


function AM_addError(html_box) {
  var error_div  = AM_getElement('erros_area');

  error_div.innerHTML =   AM_addCSSMessage() + html_box;
}

function AM_addMessage(html_box) {
  var error_div  = AM_getElement('messages_area');

  error_div.innerHTML = AM_addCSSMessage() + html_box;
}


function AM_addCSSFile(file) {
  var head = document.getElementsByTagName("HEAD")[0];
  var link = document.createElement("LINK");
  
  link.type = "text/css";
  link.rel = "stylesheet";
  link.href = CMAPP['css_url']+"/"+file;

  head.appendChild(link);
}

function AM_addJSFile(file) {
  var head = document.getElementsByTagName("HEAD")[0];
  var link = document.createElement("SCRIPT");
  
  link.type = "text/javascript";
  link.src = CMAPP['js_url']+"/"+file;

  head.appendChild(link);
}


function AM_parseRequires(requires) {

  for(req in requires) {
    
    var item = requires[req];

    switch(item.type) {
    //CMHTMlObj::MEDIA_CSS
    case 3: 
      //test if the css file was alredy loaded
      var el = document.getElementsByTagName("LINK");
      for(var i in el) {
	if(el[i].href==(CMAPP['css_url']+"/"+item.file)) break;
      }

      AM_addCSSFile(item.file);
      break;

    //CMHTMlObj::MEDIA_JS
    case 2:
      var el = document.getElementsByTagName("SCRIPT");
      for(var i in el) {
	if(el[i].src==(CMAPP['css_url']+"/"+item.file)) break;
      }

      AM_addCSSFile(item.file);
      break;
      
    }
  }
 
}

function AM_setLoading(div_name) {
  div = AM_getElement(div_name);
  div.innerHTML = "<img src='"+CMAPP['imlang_url']+"/load.gif'>";
}

function AM_unsetLoading(div_name, message) {
  div = AM_getElement(div_name);
  div.innerHTML = message;
}

AM_callBack = {
  onError : function (result) {
  	var e = document.createElement('DIV');
    e.innerHTML = result.message;
    AM_getElement('erros_area').appendChild(e);
  }
};
var lyr;
function openWindow() {
	lyr = new DynLayer(null,270,210,400,210,"#000000");
	//lyr.setID("target");
	//lyr.setOverflow("none");
	lyr.setZIndex(500);

	//block main window
	//Screen.lock();

	//lyr.setHTML("&nbsp;Teste MERDAAAAAAA!!!!!<a onClick='lyr._destroy();'>teste</a>");

	lyr.slideTo(470,330,10,1);
	DragEvent.enableDragEvents(lyr);
	dynapi.document.addChild(lyr,'target');
	
	w = new DynLayer();
	w.setBgColor('silver');
	w.setSize(130,130);
	w.setLocation(250,50);
	dynapi.document.addChild(w);
	w.slideTo(500, 100);
	DragEvent.enableDragEvents(w);
}

function toggleActive(element, elName,node) {
	var a = element.getElementsByTagName('a');
	
	var Class = element.getAttribute('class');
	var aux = "treenode_"+node;
	if(Class.indexOf('active') != '-1' ){
		element.setAttribute('class',elName+'_txt'); 
	} else {
		element.setAttribute('class', elName+'_txt_active');
	}
}