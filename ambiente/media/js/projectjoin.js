

var AMBProjectGroupActionCallBack = {
  listgroup: function(result) {
    AM_parseRequires(result.requires);

    div = AM_getElement('projectGroupList');
    div.innerHTML= result.list;
  }

}

function loadProjrectGroup(group) {
  AM_setLoading("projectGroupList");
  AMBProjectGroup.listgroup(group);
} 

/*
 *  FUNCTIONS AND CLASSES FOR THE PROJECT JOIN
 *
 *  @see AMBProjectJoin, AMBProjectJoinAction
 */


var AMBProjectJoinActionCallBack = {
  join: function(result) {
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
  AMBProjectJoin.join(project,text);
}

