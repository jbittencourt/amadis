/**Context menu Script IE5
 *
 *Credits: Dynamic Drive
 *Last updated: 08/22/01
 *
 *Description: With IE 5 and now NS6.1+, you can add a context menu to your webpage. What's a context menu? 
 *Well, it's a custom menu that pops up in place of the default context menu when you right click your mouse.
 *This custom menu can do virtually anything you want it to do, although in this script, it's designed to go to a set
 *of URLs. Also note that window targeting is possible with each link, so the links can be specified to open either
 *in current window, or another. See footnote for more info on this.
 */

//set this variable to 1 if you wish the URLs of the highlighted menu to be displayed in the status bar

var display_url=0;
var menuobj;

function defaultContextMenuItems() {
  var menuContent = new Array();
  
  menuContent.push(new Array("teste","hahahahah"));
 
  return menuContent;
}

/*
 *Os items para o ContextMenu devem seguir o seguinte padrao.
 *Array [0] = Array('lable','function');
 *
*/
function addContextMenuItems(items) {
  var output = "";

  output += "<img src='"+images_url+"/dot.gif' height='10'>\n";
  output += "<table cellspacing='0' cellpadding='0' border='0' width=''>\n";
  
  for(var i in items) {
    output += "<tr><td><img src='"+images_url+"/dot.gif' width='10'></td>\n";
    output += "<td valign='top'>\n";
    output += "<a class='cursor' onClick='"+items[i][1]+"'><b><font color='#996633'>"+items[i][0]+"</font></b></a>\n";
    output += "</td><td valign='top'><img src='"+images_url+"/dot.gif' width='25'></td>\n";
    output += "</tr>\n";
  }
  output += "</table>\n";
  //output += "<img src='"+images_url+"/dot.gif' height='10'>\n";
  return output;
}

function showContextMenu(e){
  
  //Find out how close the mouse is to the corner of the window
  
  //er = /(show_)[0-9]{1,}/;
  //er = /(CM_*)/;
  for(var i in AM_handlers) {
    
    if(String(e.target.id).indexOf(AM_handlers[i]) != -1) {
      
      var rightedge=is_ie5? document.body.clientWidth-event.clientX : window.innerWidth-e.clientX;
      var bottomedge=is_ie5? document.body.clientHeight-event.clientY : window.innerHeight-e.clientY;
      
      //if the horizontal distance isn't enough to accomodate the width of the context menu
      if (rightedge<menuobj.offsetWidth) {
	//move the horizontal position of the menu to the left by it's width
	var left=is_ie5? document.body.scrollLeft+event.clientX-menuobj.offsetWidth : window.pageXOffset+e.clientX-menuobj.offsetWidth;
      } else {
	//position the horizontal position of the menu where the mouse was clicked
	var left=is_ie5? document.body.scrollLeft+event.clientX : window.pageXOffset+e.clientX;
      }
      //same concept with the vertical position
      if (bottomedge<menuobj.offsetHeight) {
	var top=is_ie5? document.body.scrollTop+event.clientY-menuobj.offsetHeight : window.pageYOffset+e.clientY-menuobj.offsetHeight;
      } else {
	var top=is_ie5? document.body.scrollTop+event.clientY : window.pageYOffset+e.clientY;
      
      }

      //for(var i in menuobj.style) { document.write(i+"=================="+menuobj.style[i]); }
      menuobj.style.top = top;
      menuobj.style.left = left;
      func = AM_handlers[i];
      target = String(e.target.id);
      temp = target.substring((func.length+1), target.length);
      
      param = temp.split("|");

      var items = eval(func+"('"+param+"');");

      menuobj.innerHTML = addContextMenuItems(items);
      menuobj.style.top = top+"px";
      menuobj.style.left = left+"px";
      menuobj.style.visibility="visible";

      return false;

    }else if(e.target.tagname == undefined) {
      
      hideContextMenu(e);
      
    }

  }//end for 
  
}

function hideContextMenu(e){
  menuobj.style.visibility="hidden";
}

function initAMContextMenu() {
  if (is_ie5||is_ns5){
    menuobj=AM_getElement("AMContextMenu");
    menuobj.style.visibility='hidden';
    menuobj.style.top = 0;
    menuobj.style.left = 0;
    document.oncontextmenu=showContextMenu;
    document.onclick=showContextMenu;
  }
}

var userinfo_url = ""
var loading_message = "Loading userinfo data";

function switchContextMenuHTML(info) {
  var _y;
  var _x;

  _y = getBlockElement("AMContextMenu");
  frame = getBlockElement("contextmenu_iframe");

  if((_y==null) || (frame==null)) return false;

  if(is_ie) {
    _x = frame.contentWindow.document.body.innerHTML;
  }
  else {
    _x = frame.contentDocument.body.innerHTML;
  }

  _y.innerHTML = _x;
  
  return true;
}

//function amuserinfo(user,name,email,dtnas,media) {
function getContextMenuInfo(info) { 
  var ret = "";
  return info;
  ret+= loading_message;

  frame = getBlockElement("contextmenu_iframe");
  frame.src = info;

  return ret;
}

function initFrameMenu() {
  var ret="";

  ret+= "<IFRAME style=\"display:none \" id=\"contextmenu_iframe\"  onLoad=\"switchContextMenuHTML()\" scrolling=no frameborder=0 \">";
  ret+= "Could not load data.";
  ret+= "</IFRAME>";
  document.write(ret);
}