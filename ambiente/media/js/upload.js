function UploadDownload(form, dir, upload_type, codeProjeto, codCourse) {
  var cont = 0;
  for(var i = 1; i <= numItems; i++) {
    
    var nameObj = "frm_chk_row_"+i;
    
    var obj = form.elements[nameObj];
    
    if(obj.checked == true) {
      cont++;
    }
  }
  if(cont == 0) alert(lang_not_selected_files);
  else {
    form.action += "?action=A_download&frm_upload_type="+upload_type+"&frm_dir="+dir;
    if(codeProjeto != "") form.action += "&frm_codeProjeto="+codeProjeto;
    //else if(codCourse != "") form.action += "&frm_codCourse="+codCourse;
    form.submit();
  }
  
}


function UploadCheckOverwrite(form, list){
  var msg = lang_overwrite_files+"\n";
  var msgF = "";
  
  for(var i=0; i<5; i++) {
    var filePath = String(form.elements["frm_file_"+i].value);
    var fileName = filePath.substr(filePath.lastIndexOf("/")+1);
    for(var j in list.elements) {
      if(fileName == list.elements[j].value)
	msgF += " - " + fileName+ "\n";
    }
  }
  
  msg+=msgF;
  var len = parseInt(msgF.length);
  if(len != 0) {
    if(confirm(msg)) 
      return true;
    else return false;
  }else return true;
} 


//function UploadDelete(form, numItems, dir, upload_type, codeProjeto, codCourse) {
function UploadDelete(fileId) {
  
  var msg = lang_fields_to_delete+"\n";

  form = AM_getElement("form_upload");

  if(numItems == '') {
    alert(lang_not_delete_empty_diretory);
    return false;
  } else numItems = parseInt(numItems);

  var cont = 0;
  
  if(fileId != null) {
    param = fileId.split(",");
    
    var obj = form.elements["frm_chk_row_"+param[1]];
    obj.checked = true;
    var name = new String(param[0]);
    msg = msg+" - "+name+"\n";
    cont++;

  }else {  
    
    for(var i = 1; i <= numItems; i++) {
      
      var nameObj = "frm_chk_row_"+i;
      
      var obj = form.elements[nameObj];
      
      if(obj.checked == true) {
	var name = new String(obj.name);
	var realName = name.substr(9);
	msg = msg+" - "+realName+"\n";
	cont ++;
      }
    }
  }
  
  if(cont != 0) {
    if(confirm(msg)) {
      form.action = form.action+"?action=A_delete&frm_upload_type="+upload_type+"&frm_dir="+dir;
      if(codeProjeto != "") form.action += "&frm_codeProjeto="+codeProjeto;
      //else if(codeCourse != "") form.action += "&frm_codCourse="+codeCourse;
      form.submit();
    }// else return false;
  }else alert(lang_not_selected_files);
}


function UploadNewFolder(urlBase, upload_type, dir, codeProjeto, codCourse) {
  var nomFolder, url;
  nomFolder = prompt(lang_new_folder_name);
  
  if (!UploadCheckValidName(nomFolder)) {
    if(nomFolder == '' || nomFolder == null) return false;
    else {
      url  = urlBase+"?action=A_create_dir&frm_upload_type="+upload_type;
      url += "&frm_dir="+dir+"&frm_dirName="+nomFolder;
      if(codeProjeto != "") url += "&frm_codeProjeto="+codeProjeto;
      //else if(codCourse != "") url += "&frm_codCourse="+codCourse;
      redirectPage(url);
    }
  }else {
    alert(lang_invalid_folder_name);
  }
}

//name,id,type
function UploadOpenFile(file) {

  param = file.split(",");

  switch(param[2]) {
  case "img01":
  case "img02":
  case "img03":
    handle = window.open(popUrlBase+"/"+param[0],"viewFile", "width=500, height=400, scrollbars=no, status=no");
    break;
   case "html":
     handle = window.open(popUrlBase+"/"+param[0],"viewFile", "width=700, height=600, status=no, srollbars=auto");
    break;
  default:
    handle = window.open(popUrlBase+"/"+param[0],"viewFile", "width=700, height=600, status=no, scrollbars=auto");
    break;
  }
}

//name,id,type
function UploadOpenFolder(file) {
  param = file.split(",");
  url  = baseUrl+"?frm_dir=/"+param[0]+"&frm_upload_type="+upload_type;
  if(codeProjeto != '') url+= "&frm_codeProjeto="+codeProjeto;
  //if(codeCourse != '') url += "&frm_codeCourse="+codeCourse+"&"+path;
  location.href=url;
}

function UploadUnzip(file) { 
  param = file.split(",");
  path = "action=A_unzip_file&frm_filename="+param[0];
  url  = baseUrl+"?frm_dir="+dir+"&frm_upload_type="+upload_type+"&frm_codeProjeto="+codeProjeto;
  //url += "&frm_codeCourse="+codeCourse+"&"+path;
  location.href=url;
}

function UploadCheckValidName(name) {
  var checkNomFolder = new RegExp("[!@#$%^&*{}]");
  return checkNomFolder.test(name);
}

function UploadGetName(obj) {
  
}

//name,id,type
function UploadEditFile(file) {
  var param = file.split(",");
  
  url  = baseUrl+"?action=A_open_file&frm_upload_type="+upload_type;
  url += "&frm_dir="+dir+"&frm_filename="+param[0];
  if(codeProjeto != "") url += "&frm_codeProjeto="+codeProjeto;
  //else if(codeCourse != "") url += "&frm_codCourse="+codeCourse;
  redirectPage(url);
  
}

function UploadNewFile() {
  var nomFile, url;
  var msg = lang_file_exists+"\n";
  var msgF = "", cont = 0;

  var form = AM_getElement("form_upload");

  nomFile = prompt(lang_new_file_name);

  if (!UploadCheckValidName(nomFile)) {
    if(nomFile == '') return false;
    else {
      
      for(var i = 1; i <= numItems; i++) {
	
	var nameObj = "frm_chk_row_"+i;

	var obj = form.elements[nameObj];
        
	var name = new String(obj.name);
	var realName = name.substr(9);

	if(realName == nomFile) {
	  msgF = msgF+" - "+realName+"\n";
	}
	cont++;
      }
     
      msg+=msgF;

      var len = parseInt(msgF.length);
      if(len != 0) {
	alert(msg);
	return false;
      }else {
	url  = baseUrl+"?action=A_create_file&frm_upload_type="+upload_type;
	url += "&frm_dir="+dir+"&frm_filename="+nomFile;
	if(codeProjeto != "") url += "&frm_codeProjeto="+codeProjeto;
	//else if(codeCourse != "") url += "&frm_codCourse="+codeCourse;
	redirectPage(url);
      }
    }
    
  }else {
    alert(lang_invalid_file_name);
  }
}

function Upload_img(targetId) {
  //var menuContent = defaultContextMenuItems();
  
  var menuContent = new Array();
  menuContent.push(new Array("Deletar","UploadDelete(\""+targetId+"\");"));
  //menuContent.push(new Array("Renomear","UploadRename(\""+targetId+"\");"));
  menuContent.push(new Array("Abrir","UploadOpenFile(\""+targetId+"\");"));
  
  return menuContent;
}

function Upload_zip(targetId) {
  var menuContent = new Array();
  menuContent.push(new Array("Deletar","UploadDelete(\""+targetId+"\");"));
  //menuContent.push(new Array("Renomear","UploadRename(\""+targetId+"\");"));
  menuContent.push(new Array("Unzip","UploadUnzip(\""+targetId+"\");"));
  
  return menuContent;
  
}

function Upload_html(targetId) {
  var menuContent = new Array();
  menuContent.push(new Array("Deletar","UploadDelete(\""+targetId+"\");"));
  //menuContent.push(new Array("Renomear","UploadRename(\""+targetId+"\");"));
  menuContent.push(new Array("Editar","UploadEditFile(\""+targetId+"\");"));
  menuContent.push(new Array("Abrir","UploadOpenFile(\""+targetId+"\");"));

  return menuContent;
}

function Upload_pasta(targetId) {
  var menuContent = new Array();
  menuContent.push(new Array("Deletar","UploadDelete(\""+targetId+"\");"));
  //menuContent.push(new Array("Renomear","UploadRename(\""+targetId+"\");"));
  menuContent.push(new Array("Abrir","UploadOpenFolder(\""+targetId+"\");"));

  return menuContent;
}

function Upload_outro(targetId) {
  var menuContent = new Array();
  menuContent.push(new Array("Deletar","UploadDelete(\""+targetId+"\");"));
  //menuContent.push(new Array("Renomear","UploadRename(\""+targetId+"\");"));
  menuContent.push(new Array("Abrir","UploadOpenFile(\""+targetId+"\");"));
  
  return menuContent;
}

function Upload_img01(targetId) { return Upload_img(targetId); }
function Upload_img02(targetId) { return Upload_img(targetId); }
function Upload_img03(targetId) { return Upload_img(targetId); }

//registro de chamadas para o AMContextMenu
//handlers para imagens
//AM_registerHandlerId("Upload_img");
AM_registerHandlerId("Upload_img01");
AM_registerHandlerId("Upload_img02");
AM_registerHandlerId("Upload_img03");

AM_registerHandlerId("Upload_zip");
AM_registerHandlerId("Upload_html");
AM_registerHandlerId("Upload_pasta");
AM_registerHandlerId("Upload_outro");

//registro da funcao de inicializacao do editor
AM_registerEditorInitActions("UploadSetupEditor");

//registro de novos botoes para o editor html
function UploadSetupEditor() {
  AM_registerEditorButtons({name:"btn-save",
		  separator: "linebreak",
		  properties:[lang_save,
			      imlang_url+"/ico_savepage.gif",
			      false
		  ],
		  regInfo: {
		    id       : "btn-save",
		    tooltip  : lang_save,
		    image    : images_url+"/htmlarea/ed_save.gif", //imlang_url+"/ico_savepage.gif",
		    textMode : false,
		    action   : function(editor) {
		      var form = window.document.getElementById("form_file");
		      var textarea = window.document.getElementById("frm_file_content");
		      textarea.value = editor.getHTML();
		      form.submit();
		    },
		    // will be disabled if outside a <p> element
		  }
  });

}

function UploadGetSrcDir() {
  var src = chooserUrl+"?";

  if(codeProjeto != '') {
    src += "codeProjeto="+codeProjeto+"&frm_upload_type=project";
  //}else if(codeCourse != '') {
  //  src += "codeCourse="+codeCourse+"&frm_upload_type=course";
  }else {
    src += "frm_upload_type=user";
  }
  return src;
}