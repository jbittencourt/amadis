
var Forum_ImageOn = new Image();
var Forum_ImageOff = new Image();

var Forum_ImageAllOn = new Image();
var Forum_ImageAllOff = new Image();

var Forum_ImageThreadOn = new Image();
var Forum_ImageThreadOff = new Image();

var all_open=false;

var old_div = null;
var old_title = null;

var message_forum_delete = "Do you really want to delete this message?";
var delete_url = "";

function Forum_preLoadImages(image_on,image_off,image_all_on,image_all_off,image_thread_on,image_thread_off) {
  Forum_ImageOn.src = image_off;
  Forum_ImageOff.src = image_on;
  Forum_ImageAllOn.src = image_all_on;
  Forum_ImageAllOff.src = image_all_off;
  Forum_ImageThreadOn.src = image_thread_on;
  Forum_ImageThreadOff.src = image_thread_off;
}


function Forum_toggleMessage(id) { 
  div = AM_getElement(id);
  img = AM_getElement("img_"+id);
  
  if(div.style.display=="block") {
    div.style.display="none";
    img.src = Forum_ImageOff.src;
  }
  else {
    div.style.display="block";
    img.src = Forum_ImageOn.src;
  }

 
}

function Forum_cancelReply() {
  old_div.style.display="none";
}


/* This funcion display the reply form in in the message div.
 *  To effiency pourposes, it doesn't rewrite the entire frame content, 
 *  beacuse that would generate an new instance of the rich text editor.
 *  In old machines, this can be very slow. So in the HTML document,
 *  there is an <div> called reference div, that render the form. What,
 *  we do is to append that div to the divs that exists in the requested message.
 *  Using this technic, the DOM object travels around the DOM document, mantaining,
 *  it properties.
 */
function Forum_displayReply(div_name,message_code,title) {
  var form_parent;
  var reference_div = null;

  //get the elements of the objetcts
  div = AM_getElement(div_name);
  reference_div = AM_getElement("reference_div");
  form_parent = AM_getElement("frm_parent");
  form_title =  AM_getElement("frm_title");
  form_action = AM_getElement("frm_action");


  var frame = AM_getElement('frm_body');
  var fdoc = AM_getIFrameDocument(frame,'frm_body');
  var html = fdoc.body.innerHTML;

  form_action.value = "A_post";

  div.appendChild(reference_div);

  //calls a function from richtext.js to
  //re-enable the rte editing
  enableDesignMode('frm_body',html,false);

  if((old_title==null) || (old_title==form_title.value) || (form_title.value=="")) {
    form_title.value = title;
    old_title = title;
  }

  if(old_div!=null) { 
    old_div.style.display="none";
  }

  //change the parent value to the new message
  form_parent.value = message_code;
  //show the div
  div.style.display="block";
  reference_div.style.display="block";
  old_div = div;

}


function Forum_displayEdit(div_name,message_code,title) {
  var form_parent;
  var reference_div = null;

  var name  = 'body_forum_message_'+message_code;

  //get the elements of the objetcts
  div = AM_getElement(div_name);
  div_message = AM_getElement(name);
  reference_div = AM_getElement("reference_div");
  form_parent = AM_getElement("frm_parent");
  form_title =  AM_getElement("frm_title");
  form_action = AM_getElement("frm_action");

  form_action.value = "A_edit";
  html = div_message.innerHTML;

  div.appendChild(reference_div);

  //calls a function from richtext.js to
  //re-enable the rte editing
  enableDesignMode('frm_body',html,false);

  div.style.display="block";
  reference_div.style.display="block";

  form_parent.value = message_code;
  form_title.value = title;
}


function Forum_deleteMessage(code,forum) {
  if(confirm(message_forum_delete)) {
    window.location = delete_url + "?frm_codeForum="+forum+"&frm_action=A_delete&frm_code="+code;
  }
}


function Forum_openMessage(id) {
    div = AM_getElement(id);
    if(div==null) return false;

    img = AM_getElement("img_"+id);
    div.style.display="block";
    img.src = Forum_ImageOn.src;
}

function Forum_closeMessage(id) {
    div = AM_getElement(id);
    if(div==null) return false;

    img = AM_getElement("img_"+id);
    div.style.display="none";
    img.src = Forum_ImageOff.src;
}

function Forum_toogleThread(id) {
  var img = AM_getElement('img_thread_'+id);

  if(img.src == Forum_ImageThreadOff.src) {
    //thread message is open, closeit
    img.src = Forum_ImageThreadOn.src;
    Forum_toogleDescendents(id,false);
  } else {
    img.src = Forum_ImageThreadOff.src;
    Forum_toogleDescendents(id,true);
  }	

  
}


function Forum_toogleDescendents(id,open) {
  var el = AM_getElement("super_"+id);
  var x = el.childNodes;
  
  //creates a regular experssion to find the code
  //of the forum message analysing its id. So
  //I don't need to build a tree of the dependencies
  //of the forum messages
  var re = /super_forum_message_(\d+)/;
  
  if(open) {
    Forum_openMessage(id);
  }
  else {
    Forum_closeMessage(id);
  }
  for(var i=0; i < x.length; i++) {
    if(x[i].nodeName=="DIV") {
      var xid = x[i].id;
      if(re.test(xid)) {
	var parts = re.exec(xid);
	var mnum = parts[1];
	Forum_toogleDescendents("forum_message_"+mnum,open);
      }
    }
  }
  
}


function Forum_addMessage(div_name) {
  forum_messages[forum_messages.length] = div_name;
}


function Forum_closeAllMessages() {

  Forum_toogleDescendents('forum_messages',false);
   
  img = AM_getElement("img_handle_all");
  img.src = Forum_ImageAllOn.src;
  all_open = false;
}

function Forum_openAllMessages() {
  Forum_toogleDescendents('forum_messages',true);

  img = AM_getElement("img_handle_all");
  img.src = Forum_ImageAllOff.src;
  all_open = true;
}

function Forum_toogleAllMessages(handler) {
  if(all_open) {
    Forum_closeAllMessages();
    dcomSendRequest(handler+"?frm_status=closed");
  }
  else {
    Forum_openAllMessages();
    dcomSendRequest(handler+"?frm_status=open");
  }
}

