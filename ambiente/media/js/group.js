
var a1 = 'accept-box-';
var r1 = 'reject-box-';

var g = 'group-buttons-';

var GroupMembersRequestCount=0;


var AMBProjectJoinActionCallBack = {

  join: function(result) {
  },

}


var AMBGroupRequestActionCallBack = {

  handleResponse: function(result) {
    
    AM_parseRequires(result.requires);
    if(result.success==true) {
      AM_addMessage(result.message);
      AM_hiddeDiv('request-'+result.request);
      
      GroupMembersRequestCount--;
      if(GroupMembersRequestCount==0) {
	AM_hiddeDiv('projectRequestBox');
      }
    }
    else {
      AM_addMessage(result.message);
    }
  },

  accept: function(result) {
    return this.handleResponse(result);
    loadProjectGroup(result.group)
  },

  reject: function(result) {
    return this.handleResponse(result);
  }


}


function doRequest(form,code) {

  AM_hiddeDiv(g+code);
  
  AM_hiddeDiv(a1+code);
  AM_hiddeDiv(r1+code);

  AM_showDiv(form+code);
}

function doRequestCancel(code) {
  AM_showDiv(g+code);
   
  AM_hiddeDiv(a1+code);
  AM_hiddeDiv(r1+code);
}



function acceptUserJoin(codeRequest,codeGroup,codeUser) {
  var form  = AM_getElement('join-request-accept-'+codeRequest);

  text = form.frm_text.value;

  AM_setLoading('accept-box-'+codeRequest);
  AMBGroupRequest.accept(codeRequest,codeGroup,codeUser,text);
}


function rejectUserJoin(codeRequest,codeGroup,codeUser) {
  var form  = AM_getElement('join-request-reject-'+codeRequest);

  text = form.frm_text.value;

  AM_setLoading('reject-box-'+codeRequest);
  AMBGroupRequest.reject(codeRequest,codeGroup,codeUser,text);
}


