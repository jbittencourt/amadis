


function doRequest(form) {
  var a1 = 'accept-box';
  var r1 = 'reject-box';
  
  AM_hiddeDiv(a1);
  AM_hiddeDiv(r1);

  AM_showDiv(form);
}

function Group_ackResponse(codeJoin,type) {

  var link = '../../ferramentas/webfolio/ackgroupjoin.php?frm_codeGroupMemberJoin='+codeJoin;
  dcomSendRequest(link);

  var el = AM_getElement('group-invitation-'+codeJoin);
  el.style.display = 'none';
  if(type=='a') {
    count_accepted--;
    if(count_accepted==0) {
      el = AM_getElement('accept-box-display');
      el.style.display = 'none';
    }
  }
  else {
    count_rejected--;
    if(count_rejected==0) {
      el = AM_getElement('reject-box-display');
      el.style.display = 'none';
    }

  }

}