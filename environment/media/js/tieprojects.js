 
var msg_check_some_user = "You must check some project to tie before click in the button.";

function checkUsers(form,checkboxName) {

  var action= form['action'].value;

  if(action!="A_invite") {
    return true;
  }

  var size = eval("form['"+checkboxName+"'].length");
  //if there is only one checkbox in the page, tha eval above will return undefined
  //so we can detected and resolve the problem.
  if(size==null) {
    el = eval("form['"+checkboxName+"']");
    if(el.checked) {
	return true;
    }
  }
  else {
    var el = null;
    for(var i=0;i<size;i++) {
      el = eval("form['"+checkboxName+"']["+i+"]");
      if(el.checked) {
	return true;
      }
    }
  }
    
  alert(msg_check_some_user);

  return false;
}