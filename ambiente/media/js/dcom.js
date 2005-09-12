var dcom;
var dcom_doc;
var command_count = 0;
var dcom_debug = false; //set this variable to make dcom frame visible

function initDCOM(start_page) {
  var src = '<iframe src="'+start_page+'" id="dcom"';
  if(dcom_debug) {
    src+= ' style="display: absolute;z-index: 1000; clear: left\"; border:dashed green;"  width="90%" height="300" ';
  }
  else {
    src += ' style="display:none; clear: left\";" ';
  }
  src+= '></iframe>';
  
  document.write(src);
}


function dcomSendRequest(request) {
  dcom = AM_getElement('dcom');
  dcom_doc = AM_getIFrameDocument(dcom,'dcom');

  var frame_name = 'dcom_'+command_count;
  command_count++;

  var myElement = window.document.createElement("DIV");
  myElement.innerHTML = "<iframe id=\""+frame_name+"\" src='"+request+"'width='400' height='160'></iframe> " ;
  dcom_doc.body.appendChild(myElement);
  return frame_name;  
}
