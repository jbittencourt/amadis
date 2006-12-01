

var AMBProjectGroupActionCallBack = {
  onListGroup: function(result) {
    AM_parseRequires(result.requires);

    div = AM_getElement('projectGroupList');
    div.innerHTML= result.list;
  }

}

function loadProjectGroup(group) {
  AM_setLoading("projectGroupList");
  AMBProjectGroup.onListGroupError = AM_callBack.onError;
  AMBProjectGroup.listGroup(group, AMBProjectGroupActionCallBack.onListGroup);
} 

/*
 *  FUNCTIONS AND CLASSES FOR THE PROJECT JOIN
 *
 *  @see AMBProjectJoin, AMBProjectJoinAction
 */


var AMBProjectJoinActionCallBack = {
  onJoin: function(result) {
    AM_parseRequires(result.requires);

    div = AM_getElement('project_join');
    div.innerHTML= result.blockMessage;

    AM_addMessage(result.message);
  }
}



function sendProjectJoin() {
  var form  = AM_getElement('form_project_join');

  text = form.frm_text.value;
  project = form.frm_codeProject.value;

  AM_setLoading("project_join");
  AMBProjectJoin.onJoinError = AM_callBack.onError;
  AMBProjectJoin.join(project,text,AMBProjectJoinActionCallBack.onJoin);
}

