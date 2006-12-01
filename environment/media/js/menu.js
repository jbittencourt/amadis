

function sendMenuStatus(menu,menu_name,handler) {
  var status = eval(menu_name+'_open');
  dcomSendRequest(handler+'?frm_menu='+menu+'&frm_status='+status);
}