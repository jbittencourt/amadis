var is_dom = (document.getElementById) ? true : false;
var is_ns5 = ((navigator.userAgent.indexOf("Gecko")>-1) && is_dom) ? true: false;
var is_ie5 = ((navigator.userAgent.indexOf("MSIE")>-1) && is_dom) ? true : false;
var is_ns4 = (document.layers && !is_dom) ? true : false;
var is_ie =  (document.all) ? true: false;
var is_ie4 = (document.all && !is_dom) ? true : false;
var is_nodyn = (!is_ns5 && !is_ns4 && !is_ie4 && !is_ie5) ? true : false;


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
  document.write("<table cellspacing=0 cellpadding=0 border=1>");
  document.write("<tr><td><b>Property</b></td><td><b>Type</b></td></tr>");
  for (var i in obj) {
    var property = obj[i];
    out += "<tr><td>"+i+"</td>";
    out += "<td>"+property+"</td></tr>";
   
  }
  out += "</table>";
  debug = document.createElement("DIV");
  debug.innerHTML = out;
  document.body.appendChild(debug);
}

function AM_changeImage(id,varImg) {
  img = AM_getElement(id);
  img.src = varImg.src;
}

var AM_editorButtons = [];
/*  Button example to HTMLArea toolbar
 *
 *  button = {name:"btn-name",        //button name
 *	    separator: "linebreak",   //toolbar separator [separator, space, linebreak, textindicator]
 *	    properties: ["btn-label", //button label
 *			 "tooltip",   //tooltip
 *			 "image.gif", //button image
 *			 false        //disable in text mode
 *	    ],
 *	    regInfo: {
 *	      id       : "btn-name",         // the ID of your button
 *	      tooltip  : "tip",              // the tooltip
 *	      image    : "image.gif",        // image to be displayed in the toolbar
 *	      textMode : false,              // disabled in text mode
 *	      action   : function(editor) {  // called when the button is clicked
 *		//put your action script here
 *	      },
 *	      // will be disabled if outside a <p> element
 *	    }
 * };
 */
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